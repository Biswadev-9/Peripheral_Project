<?php
require_once __DIR__ . '/functions.php';
$product = $product ?? [];
$statusClass = strtolower(str_replace(' ', '-', $product['status'] ?? 'Available'));
?>
<article class="product-card">
    <a href="<?= url('product.php?slug=' . e($product['slug'])) ?>">
        <img class="product-image" src="<?= e(product_image($product['image_url'] ?? null)) ?>" alt="<?= e($product['name']) ?>">
    </a>
    <div class="p-3">
        <div class="d-flex justify-content-between gap-2 mb-2">
            <span class="badge text-bg-light"><?= e($product['brand']) ?></span>
            <span class="status-pill status-<?= e($statusClass) ?>"><?= e($product['status']) ?></span>
        </div>
        <h5 class="mb-1"><a class="text-reset" href="<?= url('product.php?slug=' . e($product['slug'])) ?>"><?= e($product['name']) ?></a></h5>
        <p class="small text-secondary mb-3"><?= e($product['model']) ?> · <?= e($product['interface_type']) ?></p>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="price"><?= money($product['price']) ?></span>
            <span class="small text-secondary"><i class="bi bi-star-fill text-warning"></i> <?= e((string) $product['rating']) ?></span>
        </div>
        <div class="d-grid gap-2">
            <a class="btn btn-outline-primary" href="<?= url('product.php?slug=' . e($product['slug'])) ?>">Quick View</a>
            <form method="post" action="<?= url('cart/actions.php') ?>" class="d-flex gap-2">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                <button class="btn btn-primary flex-fill" type="submit"><i class="bi bi-bag-plus"></i> Add</button>
                <button class="btn btn-outline-primary flex-fill" type="submit" name="buy_now" value="1">Buy Now</button>
            </form>
        </div>
    </div>
</article>
