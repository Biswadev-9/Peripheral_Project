<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    verify_csrf();
    $stmt = db()->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([(int) $_POST['id']]);
    flash('success', 'Device deleted.');
    redirect('admin/devices.php');
}

$search = trim($_GET['search'] ?? '');
$categoryId = (int) ($_GET['category_id'] ?? 0);
$status = trim($_GET['status'] ?? '');
$brand = trim($_GET['brand'] ?? '');
$where = [];
$params = [];
if ($search !== '') {
    $where[] = '(p.name LIKE ? OR p.brand LIKE ? OR p.model LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($categoryId > 0) {
    $where[] = 'p.category_id = ?';
    $params[] = $categoryId;
}
if ($status !== '') {
    $where[] = 'p.status = ?';
    $params[] = $status;
}
if ($brand !== '') {
    $where[] = 'p.brand = ?';
    $params[] = $brand;
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;

$countStmt = db()->prepare("SELECT COUNT(*) FROM products p JOIN categories c ON c.id = p.category_id $whereSql");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();
$pages = max(1, (int) ceil($total / $perPage));
if ($page > $pages) {
    redirect('admin/devices.php?' . http_build_query(array_merge($_GET, ['page' => $pages])));
}

$stmt = db()->prepare("SELECT p.*, c.name AS category_name, c.icon AS category_icon FROM products p JOIN categories c ON c.id = p.category_id $whereSql ORDER BY p.created_at DESC LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$products = $stmt->fetchAll();
$categories = db()->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$brands = db()->query('SELECT DISTINCT brand FROM products ORDER BY brand')->fetchAll(PDO::FETCH_COLUMN);
$pageTitle = 'Inventory Management | Peripheral IMS';
$activeAdmin = 'devices';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
    <section class="admin-content">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="eyebrow">Inventory</span>
                <h1 class="fw-bold mt-2 mb-0">Device Management</h1>
                <p class="text-secondary mt-2 mb-0">Showing <?= count($products) ?> of <?= $total ?> devices.</p>
            </div>
            <a class="btn btn-primary" href="<?= url('admin/device-form.php') ?>"><i class="bi bi-plus-circle"></i> Create Device</a>
        </div>
        <form class="soft-card p-3 p-lg-4 mb-4" method="get">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Search</label>
                    <input class="form-control" name="search" value="<?= e($search) ?>" placeholder="Name, brand, or model">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select class="form-select" name="category_id">
                        <option value="0">All categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>" <?= $categoryId === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Brand</label>
                    <select class="form-select" name="brand">
                        <option value="">All brands</option>
                        <?php foreach ($brands as $brandName): ?>
                            <option value="<?= e($brandName) ?>" <?= $brand === $brandName ? 'selected' : '' ?>><?= e($brandName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Any status</option>
                        <?php foreach (['Available', 'Out of Stock', 'Under Maintenance'] as $statusOption): ?>
                            <option value="<?= e($statusOption) ?>" <?= $status === $statusOption ? 'selected' : '' ?>><?= e($statusOption) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-12">
                    <a class="btn btn-outline-primary" href="<?= url('admin/devices.php') ?>">Reset Filters</a>
                </div>
            </div>
        </form>

        <?php if (!$products): ?>
            <div class="empty-state">No devices matched the current filters.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <?php $statusClass = strtolower(str_replace(' ', '-', $product['status'])); ?>
                    <div class="col-md-6 col-xl-4">
                        <article class="product-card admin-inventory-card">
                            <img class="product-image" src="<?= e(product_image($product['image_url'])) ?>" alt="<?= e($product['name']) ?>">
                            <div class="p-3">
                                <div class="d-flex justify-content-between gap-2 mb-3">
                                    <span class="badge text-bg-light"><i class="bi <?= e($product['category_icon']) ?>"></i> <?= e($product['category_name']) ?></span>
                                    <span class="status-pill status-<?= e($statusClass) ?>"><?= e($product['status']) ?></span>
                                </div>
                                <h5 class="mb-1"><?= e($product['name']) ?></h5>
                                <p class="small text-secondary mb-3"><?= e($product['brand']) ?> · <?= e($product['model']) ?> · <?= e($product['interface_type']) ?></p>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="price"><?= money($product['price']) ?></span>
                                    <span class="small fw-bold <?= (int) $product['stock_quantity'] <= 10 ? 'text-danger' : 'text-secondary' ?>">
                                        <i class="bi bi-box-seam"></i> <?= (int) $product['stock_quantity'] ?> in stock
                                    </span>
                                </div>
                                <p class="small text-secondary admin-card-description"><?= e($product['description']) ?></p>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-outline-primary flex-fill" href="<?= url('admin/device-form.php?id=' . (int) $product['id']) ?>"><i class="bi bi-pencil-square"></i> Edit</a>
                                    <form method="post" class="flex-fill">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                                        <button class="btn btn-outline-danger w-100" data-confirm="Delete this device?"><i class="bi bi-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($pages > 1): ?>
                <nav class="mt-5" aria-label="Inventory pagination">
                    <ul class="pagination justify-content-center catalog-pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?<?= e(http_build_query(array_merge($_GET, ['page' => $page - 1]))) ?>">Prev</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $pages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="?<?= e(http_build_query(array_merge($_GET, ['page' => $i]))) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $pages): ?>
                            <li class="page-item"><a class="page-link" href="?<?= e(http_build_query(array_merge($_GET, ['page' => $page + 1]))) ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
