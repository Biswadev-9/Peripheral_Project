<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

if (isset($_GET['clear'])) {
    unset($_SESSION['compare_primary_id'], $_SESSION['compare_secondary_id']);
    redirect('products.php');
}

if (isset($_GET['add'])) {
    $primaryId = (int) $_GET['add'];
    $stmt = db()->prepare('SELECT id FROM products WHERE id = ?');
    $stmt->execute([$primaryId]);
    if ($stmt->fetchColumn()) {
        $_SESSION['compare_primary_id'] = $primaryId;
        unset($_SESSION['compare_secondary_id']);
        flash('success', 'Choose another device from the same category to compare.');
    }
    redirect('compare.php');
}

$primaryId = (int) ($_SESSION['compare_primary_id'] ?? 0);
if (!$primaryId) {
    $pageTitle = 'Compare Products | Peripheral IMS';
    require_once __DIR__ . '/includes/header.php';
    ?>
    <section class="section-pad">
        <div class="container">
            <div class="empty-state">Open a product details page and click Compare Product to start a comparison.</div>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = db()->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?');
$stmt->execute([$primaryId]);
$primary = $stmt->fetch();
if (!$primary) {
    unset($_SESSION['compare_primary_id'], $_SESSION['compare_secondary_id']);
    flash('warning', 'The selected product is no longer available.');
    redirect('products.php');
}

if (isset($_GET['select'])) {
    $secondaryId = (int) $_GET['select'];
    $stmt = db()->prepare('SELECT id FROM products WHERE id = ? AND category_id = ? AND id <> ?');
    $stmt->execute([$secondaryId, $primary['category_id'], $primary['id']]);
    if ($stmt->fetchColumn()) {
        $_SESSION['compare_secondary_id'] = $secondaryId;
        flash('success', 'Comparison updated.');
    } else {
        flash('danger', 'Please choose a product from the same category.');
    }
    redirect('compare.php');
}

$secondary = null;
$secondaryId = (int) ($_SESSION['compare_secondary_id'] ?? 0);
if ($secondaryId) {
    $stmt = db()->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ? AND p.category_id = ?');
    $stmt->execute([$secondaryId, $primary['category_id']]);
    $secondary = $stmt->fetch();
}

$query = trim($_GET['q'] ?? '');
$candidates = [];
if ($query !== '') {
    $stmt = db()->prepare(
        'SELECT * FROM products
         WHERE category_id = ? AND id <> ? AND (name LIKE ? OR brand LIKE ? OR model LIKE ?)
         ORDER BY name
         LIMIT 8'
    );
    $like = '%' . $query . '%';
    $stmt->execute([$primary['category_id'], $primary['id'], $like, $like, $like]);
    $candidates = $stmt->fetchAll();
} elseif (!$secondary) {
    $stmt = db()->prepare('SELECT * FROM products WHERE category_id = ? AND id <> ? ORDER BY rating DESC, name LIMIT 8');
    $stmt->execute([$primary['category_id'], $primary['id']]);
    $candidates = $stmt->fetchAll();
}

$primarySpecs = json_decode($primary['specifications'] ?? '{}', true) ?: [];
$secondarySpecs = $secondary ? (json_decode($secondary['specifications'] ?? '{}', true) ?: []) : [];
$specKeys = array_values(array_unique(array_merge(array_keys($primarySpecs), array_keys($secondarySpecs))));
$pageTitle = 'Compare Products | Peripheral IMS';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section-pad">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="eyebrow">Compare</span>
                <h1 class="fw-bold mt-2 mb-0">Compare <?= e($primary['category_name']) ?> Devices</h1>
                <p class="text-secondary mt-2 mb-0">Search and select another <?= e($primary['category_name']) ?> device for a detailed side-by-side comparison.</p>
            </div>
            <a class="btn btn-outline-primary" href="<?= url('compare.php?clear=1') ?>">Clear</a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <div class="soft-card p-4 h-100">
                    <span class="eyebrow">Selected Product</span>
                    <div class="d-flex gap-3 align-items-center mt-3">
                        <img src="<?= e(product_image($primary['image_url'])) ?>" alt="<?= e($primary['name']) ?>" class="rounded-3" style="width:110px;height:110px;object-fit:cover;">
                        <div>
                            <h4 class="fw-bold mb-1"><?= e($primary['name']) ?></h4>
                            <p class="text-secondary mb-2"><?= e($primary['brand']) ?> · <?= e($primary['model']) ?></p>
                            <span class="price"><?= money($primary['price']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <form class="soft-card p-4 h-100" method="get">
                    <label class="form-label fw-bold">Search same-category device</label>
                    <div class="input-group">
                        <input class="form-control" name="q" value="<?= e($query) ?>" placeholder="Search <?= e($primary['category_name']) ?> by name, brand, or model">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
                    </div>
                    <p class="small text-secondary mt-2 mb-0">Only <?= e($primary['category_name']) ?> products can be selected.</p>
                </form>
            </div>
        </div>

        <?php if ($candidates): ?>
            <div class="soft-card p-4 mb-4">
                <h4 class="fw-bold mb-3"><?= $query !== '' ? 'Search Results' : 'Suggested Matches' ?></h4>
                <div class="row g-3">
                    <?php foreach ($candidates as $candidate): ?>
                        <div class="col-md-6 col-xl-3">
                            <div class="spec-item h-100">
                                <img src="<?= e(product_image($candidate['image_url'])) ?>" alt="<?= e($candidate['name']) ?>" class="rounded-3 mb-3 w-100" style="height:130px;object-fit:cover;">
                                <h6 class="fw-bold mb-1"><?= e($candidate['name']) ?></h6>
                                <p class="small text-secondary mb-2"><?= e($candidate['brand']) ?> · <?= e($candidate['model']) ?></p>
                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <strong><?= money($candidate['price']) ?></strong>
                                    <a class="btn btn-sm btn-primary" href="<?= url('compare.php?select=' . (int) $candidate['id']) ?>">Compare</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php elseif ($query !== '' && !$secondary): ?>
            <div class="empty-state mb-4">No matching <?= e($primary['category_name']) ?> devices found.</div>
        <?php endif; ?>

        <?php if ($secondary): ?>
            <div class="soft-card p-3 p-lg-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:22%;">Feature</th>
                                <th><?= e($primary['name']) ?></th>
                                <th><?= e($secondary['name']) ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Product</th>
                                <td><img src="<?= e(product_image($primary['image_url'])) ?>" alt="" class="rounded-3 me-2" style="width:76px;height:76px;object-fit:cover;"> <?= e($primary['name']) ?></td>
                                <td><img src="<?= e(product_image($secondary['image_url'])) ?>" alt="" class="rounded-3 me-2" style="width:76px;height:76px;object-fit:cover;"> <?= e($secondary['name']) ?></td>
                            </tr>
                            <tr><th>Brand</th><td><?= e($primary['brand']) ?></td><td><?= e($secondary['brand']) ?></td></tr>
                            <tr><th>Model</th><td><?= e($primary['model']) ?></td><td><?= e($secondary['model']) ?></td></tr>
                            <tr><th>Category</th><td><?= e($primary['category_name']) ?></td><td><?= e($secondary['category_name']) ?></td></tr>
                            <tr><th>Price</th><td><?= money($primary['price']) ?></td><td><?= money($secondary['price']) ?></td></tr>
                            <tr><th>Interface Type</th><td><?= e($primary['interface_type']) ?></td><td><?= e($secondary['interface_type']) ?></td></tr>
                            <tr><th>Status</th><td><?= e($primary['status']) ?></td><td><?= e($secondary['status']) ?></td></tr>
                            <tr><th>Stock Quantity</th><td><?= (int) $primary['stock_quantity'] ?></td><td><?= (int) $secondary['stock_quantity'] ?></td></tr>
                            <tr><th>Rating</th><td><i class="bi bi-star-fill text-warning"></i> <?= e((string) $primary['rating']) ?></td><td><i class="bi bi-star-fill text-warning"></i> <?= e((string) $secondary['rating']) ?></td></tr>
                            <tr><th>Description</th><td><?= e($primary['description']) ?></td><td><?= e($secondary['description']) ?></td></tr>
                            <?php foreach ($specKeys as $key): ?>
                                <tr>
                                    <th><?= e((string) $key) ?></th>
                                    <td><?= e((string) ($primarySpecs[$key] ?? 'Not specified')) ?></td>
                                    <td><?= e((string) ($secondarySpecs[$key] ?? 'Not specified')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <th>Actions</th>
                                <td><a class="btn btn-outline-primary" href="<?= url('product.php?slug=' . e($primary['slug'])) ?>">View Details</a></td>
                                <td><a class="btn btn-outline-primary" href="<?= url('product.php?slug=' . e($secondary['slug'])) ?>">View Details</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
