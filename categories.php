<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Categories | Peripheral IMS';
$categories = get_categories();
require_once __DIR__ . '/includes/header.php';
?>

<section class="section-pad">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">Device Categories</span>
            <h1 class="fw-bold mt-2">Browse every computer lab peripheral category.</h1>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a class="category-card d-block text-reset" href="<?= url('category.php?slug=' . e($category['slug'])) ?>">
                        <img class="category-image" src="<?= e($category['image_url']) ?>" alt="<?= e($category['name']) ?>">
                        <div class="p-3">
                            <span class="category-icon mb-3"><i class="bi <?= e($category['icon']) ?>"></i></span>
                            <h5><?= e($category['name']) ?></h5>
                            <p class="small text-secondary"><?= e($category['description']) ?></p>
                            <span class="badge text-bg-light"><?= (int) $category['device_count'] ?> devices</span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
