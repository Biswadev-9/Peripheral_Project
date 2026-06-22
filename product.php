<?php
require_once __DIR__ . '/includes/functions.php';
$slug = $_GET['slug'] ?? '';
$stmt = db()->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p JOIN categories c ON c.id = p.category_id WHERE p.slug = ?');
$stmt->execute([$slug]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    exit('Product not found.');
}

$reviewsStmt = db()->prepare('SELECT r.*, u.name FROM reviews r LEFT JOIN users u ON u.id = r.user_id WHERE r.product_id = ? ORDER BY r.created_at DESC');
$reviewsStmt->execute([$product['id']]);
$reviews = $reviewsStmt->fetchAll();
$mainImage = product_image($product['image_url']);
$galleryItems = array_map('product_image', json_decode($product['gallery'] ?? '[]', true) ?: []);
$gallery = array_values(array_unique(array_filter(array_merge([$mainImage], $galleryItems))));
$specs = json_decode($product['specifications'] ?? '{}', true) ?: [];
$pageTitle = $product['name'] . ' | Peripheral IMS';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container breadcrumb-wrap">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('index.php') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= url('category.php?slug=' . e($product['category_slug'])) ?>"><?= e($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active"><?= e($product['name']) ?></li>
        </ol>
    </nav>
</div>

<section class="section-pad pt-3">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <img id="mainProductImage" class="gallery-main shadow-sm" src="<?= e($mainImage) ?>" alt="<?= e($product['name']) ?>">
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <?php foreach ($gallery as $index => $image): ?>
                        <img class="thumb <?= $index === 0 ? 'active' : '' ?>" data-gallery-thumb="#mainProductImage" src="<?= e($image) ?>" alt="<?= e($product['name']) ?> thumbnail">
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="badge text-bg-light mb-3"><?= e($product['brand']) ?> · <?= e($product['model']) ?></span>
                <h1 class="fw-bold"><?= e($product['name']) ?></h1>
                <div class="d-flex flex-wrap gap-3 align-items-center my-3">
                    <span class="price fs-2"><?= money($product['price']) ?></span>
                    <span class="status-pill status-<?= e(strtolower(str_replace(' ', '-', $product['status']))) ?>"><?= e($product['status']) ?></span>
                    <span class="text-secondary"><i class="bi bi-star-fill text-warning"></i> <?= e((string) $product['rating']) ?> rating</span>
                </div>
                <p class="text-secondary"><?= e($product['description']) ?></p>
                <div class="spec-grid my-4">
                    <div class="spec-item"><strong>Interface</strong><div class="text-secondary"><?= e($product['interface_type']) ?></div></div>
                    <div class="spec-item"><strong>Stock</strong><div class="text-secondary"><?= (int) $product['stock_quantity'] ?> units</div></div>
                    <div class="spec-item"><strong>Category</strong><div class="text-secondary"><?= e($product['category_name']) ?></div></div>
                    <div class="spec-item"><strong>Device Status</strong><div class="text-secondary"><?= e($product['status']) ?></div></div>
                </div>
                <form method="post" action="<?= url('cart/actions.php') ?>" class="d-flex flex-wrap gap-3 align-items-center mb-3">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <div class="quantity-control">
                        <button type="button" data-qty="#quantity" data-action="decrease">-</button>
                        <input id="quantity" name="quantity" value="1" inputmode="numeric">
                        <button type="button" data-qty="#quantity" data-action="increase">+</button>
                    </div>
                    <button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-bag-plus"></i> Add to Cart</button>
                    <button class="btn btn-outline-primary btn-lg" name="buy_now" value="1" type="submit">Buy Now</button>
                </form>
                <div class="d-flex flex-wrap gap-2">
                    <form method="post" action="<?= url('wishlist.php') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-heart"></i> Add to Wishlist</button>
                    </form>
                    <a class="btn btn-outline-primary" href="<?= url('compare.php?add=' . (int) $product['id']) ?>"><i class="bi bi-columns-gap"></i> Compare Product</a>
                </div>
            </div>
        </div>

        <div class="soft-card p-3 p-lg-4 mt-5">
            <ul class="nav nav-pills mb-4" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc" type="button">Description</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#specs" type="button">Specifications</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" type="button">Reviews</button></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="desc">
                    <p class="text-secondary mb-0"><?= e($product['description']) ?></p>
                </div>
                <div class="tab-pane fade" id="specs">
                    <div class="spec-grid">
                        <?php foreach ($specs as $key => $value): ?>
                            <div class="spec-item"><strong><?= e((string) $key) ?></strong><div class="text-secondary"><?= e((string) $value) ?></div></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="reviews">
                    <?php if (!$reviews): ?>
                        <div class="empty-state">No reviews yet.</div>
                    <?php else: ?>
                        <div class="vstack gap-3">
                            <?php foreach ($reviews as $review): ?>
                                <div class="border-bottom pb-3">
                                    <div class="d-flex justify-content-between gap-3">
                                        <strong><?= e($review['title']) ?></strong>
                                        <span class="text-warning"><?= str_repeat('★', (int) $review['rating']) ?></span>
                                    </div>
                                    <p class="text-secondary mb-1"><?= e($review['comment']) ?></p>
                                    <span class="small text-secondary">By <?= e($review['name'] ?? 'Customer') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
