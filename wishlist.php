<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $productId = (int) ($_POST['product_id'] ?? 0);
    if ($productId) {
        $stmt = db()->prepare('INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)');
        $stmt->execute([current_user()['id'], $productId]);
        flash('success', 'Product added to your wishlist.');
    }
    redirect('wishlist.php');
}

if (isset($_GET['remove'])) {
    $stmt = db()->prepare('DELETE FROM wishlist WHERE user_id = ? AND product_id = ?');
    $stmt->execute([current_user()['id'], (int) $_GET['remove']]);
    flash('success', 'Product removed from wishlist.');
    redirect('wishlist.php');
}

$stmt = db()->prepare('SELECT p.* FROM wishlist w JOIN products p ON p.id = w.product_id WHERE w.user_id = ? ORDER BY w.created_at DESC');
$stmt->execute([current_user()['id']]);
$products = $stmt->fetchAll();
$pageTitle = 'Wishlist | Peripheral IMS';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section-pad">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Wishlist</span>
            <h1 class="fw-bold mt-2">Saved products</h1>
        </div>
        <?php if (!$products): ?>
            <div class="empty-state">Your wishlist is empty.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-6 col-xl-4">
                        <?php require __DIR__ . '/includes/product-card.php'; ?>
                        <a class="btn btn-link text-danger mt-2" href="<?= url('wishlist.php?remove=' . (int) $product['id']) ?>">Remove</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
