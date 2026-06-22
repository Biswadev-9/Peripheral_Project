<?php
require_once __DIR__ . '/includes/functions.php';
$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare('SELECT * FROM categories WHERE slug = ?');
$stmt->execute([$slug]);
$category = $stmt->fetch();
if (!$category) {
    http_response_code(404);
    exit('Category not found.');
}

$stmt = db()->prepare('SELECT * FROM products WHERE category_id = ? ORDER BY name');
$stmt->execute([$category['id']]);
$products = $stmt->fetchAll();
$pageTitle = $category['name'] . ' Category | Peripheral IMS';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container breadcrumb-wrap">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= url('categories.php') ?>">Categories</a></li>
            <li class="breadcrumb-item active"><?= e($category['name']) ?></li>
        </ol>
    </nav>
</div>

<section class="section-pad pt-3">
    <div class="container">
        <div class="soft-card p-4 p-lg-5 mb-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="category-icon mb-3"><i class="bi <?= e($category['icon']) ?>"></i></span>
                    <h1 class="fw-bold"><?= e($category['name']) ?> Category</h1>
                    <p class="text-secondary mb-0"><?= e($category['description']) ?></p>
                </div>
                <div class="col-lg-4">
                    <img class="rounded-4 w-100" style="height:220px;object-fit:cover;" src="<?= e($category['image_url']) ?>" alt="<?= e($category['name']) ?>">
                </div>
            </div>
        </div>

        <?php if (!$products): ?>
            <div class="empty-state">No products found in this category yet.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col-md-6 col-xl-4">
                        <?php require __DIR__ . '/includes/product-card.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
