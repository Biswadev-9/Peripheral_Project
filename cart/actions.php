<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('cart/index.php');
}

verify_csrf();
$action = $_POST['action'] ?? '';
$cartId = get_active_cart_id();

if ($action === 'add') {
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = max(1, (int) ($_POST['quantity'] ?? 1));
    $stmt = db()->prepare('SELECT status, stock_quantity FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product || $product['status'] !== 'Available' || (int) $product['stock_quantity'] <= 0) {
        flash('danger', 'This product is not available for purchase.');
        redirect('products.php');
    }

    if (isset($_POST['buy_now'])) {
        $_SESSION['buy_now'] = [
            'product_id' => $productId,
            'quantity' => min($quantity, (int) $product['stock_quantity']),
        ];
        redirect('checkout/index.php?mode=buy_now');
    }

    unset($_SESSION['buy_now']);
    add_to_cart($productId, min($quantity, (int) $product['stock_quantity']));
    flash('success', 'Product added to cart.');
    redirect($_SERVER['HTTP_REFERER'] ?? 'products.php');
}

if ($action === 'update') {
    foreach ($_POST['quantities'] ?? [] as $itemId => $quantity) {
        $stmt = db()->prepare('UPDATE cart_items SET quantity = ? WHERE id = ? AND cart_id = ?');
        $stmt->execute([max(1, (int) $quantity), (int) $itemId, $cartId]);
    }
    flash('success', 'Cart updated.');
}

if ($action === 'remove') {
    $stmt = db()->prepare('DELETE FROM cart_items WHERE id = ? AND cart_id = ?');
    $stmt->execute([(int) ($_POST['item_id'] ?? 0), $cartId]);
    flash('success', 'Item removed.');
}

if ($action === 'save') {
    $stmt = db()->prepare('UPDATE cart_items SET saved_for_later = 1 WHERE id = ? AND cart_id = ?');
    $stmt->execute([(int) ($_POST['item_id'] ?? 0), $cartId]);
    flash('success', 'Item saved for later.');
}

if ($action === 'move_to_cart') {
    $stmt = db()->prepare('UPDATE cart_items SET saved_for_later = 0 WHERE id = ? AND cart_id = ?');
    $stmt->execute([(int) ($_POST['item_id'] ?? 0), $cartId]);
    flash('success', 'Item moved back to cart.');
}

if ($action === 'coupon') {
    $code = strtoupper(trim($_POST['coupon'] ?? ''));
    $_SESSION['coupon'] = $code === 'LAB10' ? ['code' => 'LAB10', 'rate' => 0.10] : null;
    flash($_SESSION['coupon'] ? 'success' : 'warning', $_SESSION['coupon'] ? 'Coupon LAB10 applied.' : 'Invalid coupon code.');
}

redirect('cart/index.php');
