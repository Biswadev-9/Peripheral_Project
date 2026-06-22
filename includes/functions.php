<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

const APP_NAME = 'Peripheral Inventory Management System';

if (!defined('APP_URL')) {
    define('APP_URL', detect_app_url());
}

function detect_app_url(): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = $scriptDir === '/' || $scriptDir === '.' ? '' : rtrim($scriptDir, '/');

    foreach (['/admin', '/api', '/auth', '/cart', '/checkout', '/orders'] as $section) {
        if (str_ends_with($scriptDir, $section)) {
            return substr($scriptDir, 0, -strlen($section)) ?: '';
        }
    }

    return $scriptDir;
}

function db(): PDO
{
    return Database::connection();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function redirect(string $path): never
{
    if (preg_match('#^https?://#i', $path) === 1) {
        header('Location: ' . $path);
        exit;
    }

    header('Location: ' . url($path));
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Invalid security token. Please go back and try again.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_admin(): bool
{
    return (current_user()['role'] ?? '') === 'admin';
}

function require_login(): void
{
    if (!is_logged_in()) {
        flash('warning', 'Please sign in to continue.');
        redirect('auth/login.php');
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('You do not have permission to access this page.');
    }
}

function money(float|int|string $amount): string
{
    return '$' . number_format((float) $amount, 2);
}

function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?: '';
    $value = trim($value, '-');
    return $value !== '' ? $value : 'item-' . bin2hex(random_bytes(3));
}

function product_image(?string $imageUrl): string
{
    if ($imageUrl) {
        return normalize_local_url($imageUrl);
    }

    return 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80';
}

function normalize_local_url(string $path): string
{
    if (preg_match('#^(https?:)?//#i', $path) === 1 || str_starts_with($path, 'data:')) {
        return $path;
    }

    $path = str_replace('\\', '/', $path);

    if (preg_match('#/(assets|uploads)/(.+)$#', $path, $matches) === 1) {
        return url($matches[1] . '/' . $matches[2]);
    }

    return url($path);
}

function get_categories(?int $limit = null): array
{
    $sql = 'SELECT c.*, COUNT(p.id) AS device_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id
            ORDER BY c.name';
    if ($limit !== null) {
        $sql .= ' LIMIT ' . (int) $limit;
    }

    return db()->query($sql)->fetchAll();
}

function get_active_cart_id(): int
{
    $sessionId = session_id();
    $userId = current_user()['id'] ?? null;

    if ($userId) {
        $stmt = db()->prepare('SELECT id FROM carts WHERE user_id = ? AND status = "active" LIMIT 1');
        $stmt->execute([$userId]);
    } else {
        $stmt = db()->prepare('SELECT id FROM carts WHERE session_id = ? AND status = "active" LIMIT 1');
        $stmt->execute([$sessionId]);
    }

    $cartId = $stmt->fetchColumn();
    if ($cartId) {
        return (int) $cartId;
    }

    $stmt = db()->prepare('INSERT INTO carts (user_id, session_id, status) VALUES (?, ?, "active")');
    $stmt->execute([$userId, $sessionId]);
    return (int) db()->lastInsertId();
}

function cart_items(): array
{
    $cartId = get_active_cart_id();
    $stmt = db()->prepare(
        'SELECT ci.*, p.name, p.brand, p.model, p.price, p.stock_quantity, p.image_url, p.slug
         FROM cart_items ci
         JOIN products p ON p.id = ci.product_id
         WHERE ci.cart_id = ?
         ORDER BY ci.created_at DESC'
    );
    $stmt->execute([$cartId]);

    return $stmt->fetchAll();
}

function cart_count(): int
{
    $cartId = get_active_cart_id();
    $stmt = db()->prepare('SELECT COALESCE(SUM(quantity), 0) FROM cart_items WHERE cart_id = ?');
    $stmt->execute([$cartId]);

    return (int) $stmt->fetchColumn();
}

function add_to_cart(int $productId, int $quantity = 1): void
{
    $quantity = max(1, $quantity);
    $cartId = get_active_cart_id();
    $stmt = db()->prepare('SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?');
    $stmt->execute([$cartId, $productId]);
    $existing = $stmt->fetch();

    if ($existing) {
        $update = db()->prepare('UPDATE cart_items SET quantity = quantity + ?, updated_at = NOW() WHERE id = ?');
        $update->execute([$quantity, $existing['id']]);
        return;
    }

    $insert = db()->prepare('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)');
    $insert->execute([$cartId, $productId, $quantity]);
}

function cart_totals(?array $items = null, float $discount = 0): array
{
    $items ??= cart_items();
    $subtotal = 0.0;
    foreach ($items as $item) {
        $subtotal += (float) $item['price'] * (int) $item['quantity'];
    }

    $discount = min(max($discount, 0), $subtotal);
    $tax = round(($subtotal - $discount) * 0.08, 2);
    $shipping = $subtotal > 0 && $subtotal < 500 ? 24.99 : 0.0;
    $grandTotal = max(0, $subtotal - $discount + $tax + $shipping);

    return compact('subtotal', 'discount', 'tax', 'shipping', 'grandTotal');
}

function sync_guest_cart_to_user(int $userId): void
{
    $sessionId = session_id();
    $stmt = db()->prepare('UPDATE carts SET user_id = ?, session_id = NULL WHERE session_id = ? AND status = "active"');
    $stmt->execute([$userId, $sessionId]);
}
