<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Peripheral Inventory Management System';
$featured = db()->query('SELECT * FROM products WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 6')->fetchAll();
$bestSellers = db()->query('SELECT * FROM products WHERE is_best_seller = 1 ORDER BY rating DESC LIMIT 4')->fetchAll();
$categories = get_categories(12);
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="eyebrow">Inventory + Shopping</span>
                <h1 class="mt-3 mb-4">Manage, Track & Purchase Computer Peripherals Easily</h1>
                <p class="mb-4">A modern web platform for computer laboratories to monitor device stock, discover categories, compare products, and complete purchases with invoice-ready checkout.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a class="btn btn-primary btn-lg" href="<?= url('products.php') ?>"><i class="bi bi-grid"></i> Browse Devices</a>
                    <a class="btn btn-outline-primary btn-lg" href="<?= url('categories.php') ?>"><i class="bi bi-layers"></i> Explore Categories</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-visual">
                    <div class="device-stage">
                        <div class="monitor-art"></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Categories</span>
            <h2 class="fw-bold mt-2">Find the right peripheral by lab workflow.</h2>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a class="category-card d-block text-reset" href="<?= url('category.php?slug=' . e($category['slug'])) ?>">
                        <img class="category-image" src="<?= e($category['image_url']) ?>" alt="<?= e($category['name']) ?>">
                        <div class="p-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="category-icon"><i class="bi <?= e($category['icon']) ?>"></i></span>
                                <span class="small text-secondary"><?= (int) $category['device_count'] ?> devices</span>
                            </div>
                            <h5 class="mb-1"><?= e($category['name']) ?></h5>
                            <p class="small text-secondary mb-0"><?= e($category['description']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad pt-0">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <span class="eyebrow">Featured</span>
                <h2 class="fw-bold mt-2 mb-0">Popular lab-ready devices</h2>
            </div>
            <a class="btn btn-outline-primary" href="<?= url('products.php') ?>">View all products</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featured as $product): ?>
                <div class="col-md-6 col-xl-4">
                    <?php require __DIR__ . '/includes/product-card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad pt-0">
    <div class="container">
        <div class="soft-card p-4 p-lg-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <span class="eyebrow">Best Sellers</span>
                    <h2 class="fw-bold mt-2">Devices schools keep reordering.</h2>
                    <p class="text-secondary">Best sellers help admins quickly replenish the stock students use every day.</p>
                </div>
                <div class="col-lg-7">
                    <div class="row g-3">
                        <?php foreach ($bestSellers as $product): ?>
                            <div class="col-sm-6">
                                <a class="d-flex gap-3 align-items-center soft-card p-3 text-reset" href="<?= url('product.php?slug=' . e($product['slug'])) ?>">
                                    <img src="<?= e(product_image($product['image_url'])) ?>" alt="<?= e($product['name']) ?>" class="rounded-3" style="width:76px;height:76px;object-fit:cover;">
                                    <div>
                                        <strong><?= e($product['name']) ?></strong>
                                        <div class="small text-secondary"><?= e($product['brand']) ?></div>
                                        <div class="price fs-6"><?= money($product['price']) ?></div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
