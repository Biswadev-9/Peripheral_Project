<?php
require_once __DIR__ . '/includes/functions.php';
$search = trim($_GET['search'] ?? '');
$brand = trim($_GET['brand'] ?? '');
$availability = trim($_GET['availability'] ?? '');
$type = trim($_GET['type'] ?? '');
$categoryId = (int) ($_GET['category_id'] ?? 0);
$priceRange = trim($_GET['price_range'] ?? '');
$sort = trim($_GET['sort'] ?? 'latest');
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$where = [];
$params = [];
if ($search !== '') {
    $where[] = '(p.name LIKE ? OR p.brand LIKE ? OR p.model LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($brand !== '') {
    $where[] = 'p.brand = ?';
    $params[] = $brand;
}
if ($categoryId > 0) {
    $where[] = 'p.category_id = ?';
    $params[] = $categoryId;
}
if ($availability !== '') {
    $where[] = 'p.status = ?';
    $params[] = $availability;
}
if ($type !== '') {
    $where[] = 'c.device_type = ?';
    $params[] = $type;
}
if ($priceRange !== '') {
    if ($priceRange === 'under_50') {
        $maxPrice = '50';
    } elseif ($priceRange === '50_150') {
        $minPrice = '50';
        $maxPrice = '150';
    } elseif ($priceRange === '150_300') {
        $minPrice = '150';
        $maxPrice = '300';
    } elseif ($priceRange === '300_plus') {
        $minPrice = '300';
    }
}
if ($minPrice !== '') {
    $where[] = 'p.price >= ?';
    $params[] = (float) $minPrice;
}
if ($maxPrice !== '') {
    $where[] = 'p.price <= ?';
    $params[] = (float) $maxPrice;
}

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 9;
$offset = ($page - 1) * $perPage;
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$orderBy = match ($sort) {
    'price_low' => 'p.price ASC',
    'price_high' => 'p.price DESC',
    'rating' => 'p.rating DESC, p.created_at DESC',
    'name' => 'p.name ASC',
    default => 'p.created_at DESC',
};

$countStmt = db()->prepare("SELECT COUNT(*) FROM products p JOIN categories c ON c.id = p.category_id $whereSql");
$countStmt->execute($params);
$total = (int) $countStmt->fetchColumn();

$stmt = db()->prepare("SELECT p.*, c.name AS category_name, c.device_type FROM products p JOIN categories c ON c.id = p.category_id $whereSql ORDER BY $orderBy LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$products = $stmt->fetchAll();
$brands = db()->query('SELECT DISTINCT brand FROM products ORDER BY brand')->fetchAll(PDO::FETCH_COLUMN);
$brandCounts = db()->query('SELECT brand, COUNT(*) AS total FROM products GROUP BY brand ORDER BY brand')->fetchAll();
$categories = get_categories();
$pages = max(1, (int) ceil($total / $perPage));
$pageTitle = 'Products | Peripheral IMS';
require_once __DIR__ . '/includes/header.php';
?>

<section class="section-pad product-catalog-page">
    <div class="container">
        <div class="catalog-hero mb-5">
            <span class="catalog-pill"><i class="bi bi-box-seam"></i> Storefront & Requisitions</span>
            <h1 class="fw-bold mt-3 mb-3">Peripheral Device Catalog</h1>
            <p class="text-secondary mb-0">Browse lab-ready peripherals with clean search, filters, availability, and fast purchasing controls.</p>
        </div>

        <div class="catalog-layout">
            <aside class="catalog-sidebar">
                <form method="get" id="catalogFilters">
                    <input type="hidden" name="sort" value="<?= e($sort) ?>">
                    <div class="catalog-filter-card">
                        <h5><i class="bi bi-search"></i> Search Inventory</h5>
                        <div class="catalog-search-wrap">
                            <i class="bi bi-search"></i>
                            <input name="search" value="<?= e($search) ?>" placeholder="Type to search...">
                            <button type="submit" aria-label="Search"><i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>

                    <div class="catalog-filter-card">
                        <h5><i class="bi bi-layers"></i> Peripheral Types</h5>
                        <div class="filter-scroll">
                            <label class="filter-option <?= $categoryId === 0 ? 'active' : '' ?>">
                                <input type="radio" name="category_id" value="0" <?= $categoryId === 0 ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span>All Types</span>
                                <small><?= array_sum(array_map(fn ($item) => (int) $item['device_count'], $categories)) ?></small>
                            </label>
                            <?php foreach ($categories as $category): ?>
                                <label class="filter-option <?= $categoryId === (int) $category['id'] ? 'active' : '' ?>">
                                    <input type="radio" name="category_id" value="<?= (int) $category['id'] ?>" <?= $categoryId === (int) $category['id'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                    <span><i class="bi <?= e($category['icon']) ?>"></i> <?= e($category['name']) ?></span>
                                    <small><?= (int) $category['device_count'] ?></small>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="catalog-filter-card">
                        <h5><i class="bi bi-cpu"></i> Companies / Brands</h5>
                        <div class="filter-scroll">
                            <label class="filter-option <?= $brand === '' ? 'active' : '' ?>">
                                <input type="radio" name="brand" value="" <?= $brand === '' ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span>All Brands</span>
                                <small><?= array_sum(array_map(fn ($item) => (int) $item['total'], $brandCounts)) ?></small>
                            </label>
                            <?php foreach ($brandCounts as $brandRow): ?>
                                <label class="filter-option <?= $brand === $brandRow['brand'] ? 'active' : '' ?>">
                                    <input type="radio" name="brand" value="<?= e($brandRow['brand']) ?>" <?= $brand === $brandRow['brand'] ? 'checked' : '' ?> onchange="this.form.submit()">
                                    <span><?= e($brandRow['brand']) ?></span>
                                    <small><?= (int) $brandRow['total'] ?></small>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="catalog-filter-card">
                        <h5><i class="bi bi-sliders"></i> Price Ranges</h5>
                        <?php
                        $ranges = [
                            '' => 'Any Price',
                            'under_50' => 'Under $50',
                            '50_150' => '$50 - $150',
                            '150_300' => '$150 - $300',
                            '300_plus' => '$300+',
                        ];
                        foreach ($ranges as $value => $label):
                        ?>
                            <label class="filter-option <?= $priceRange === $value ? 'active' : '' ?>">
                                <input type="radio" name="price_range" value="<?= e($value) ?>" <?= $priceRange === $value ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span><?= e($label) ?></span>
                                <?php if ($priceRange === $value): ?><small><i class="bi bi-check-lg"></i></small><?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <div class="catalog-filter-card">
                        <h5><i class="bi bi-activity"></i> Availability</h5>
                        <?php foreach (['' => 'Any Status', 'Available' => 'Available', 'Out of Stock' => 'Out of Stock', 'Under Maintenance' => 'Under Maintenance'] as $value => $label): ?>
                            <label class="filter-option <?= $availability === $value ? 'active' : '' ?>">
                                <input type="radio" name="availability" value="<?= e($value) ?>" <?= $availability === $value ? 'checked' : '' ?> onchange="this.form.submit()">
                                <span><?= e($label) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <a class="btn btn-outline-primary w-100" href="<?= url('products.php') ?>">Reset Filters</a>
                </form>
            </aside>

            <div class="catalog-results">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div class="catalog-count">DISPLAYING <?= $total ?> CUSTOM DEVICES</div>
                    <form method="get" class="catalog-sort">
                        <?php foreach ($_GET as $key => $value): ?>
                            <?php if ($key !== 'sort' && $key !== 'page'): ?>
                                <input type="hidden" name="<?= e($key) ?>" value="<?= e((string) $value) ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <label class="form-label mb-0">Sort by:</label>
                        <select class="form-select" name="sort" onchange="this.form.submit()">
                            <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Latest additions</option>
                            <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: low to high</option>
                            <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: high to low</option>
                            <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Highest rated</option>
                            <option value="name" <?= $sort === 'name' ? 'selected' : '' ?>>Name A-Z</option>
                        </select>
                    </form>
                </div>

                <?php if (!$products): ?>
                    <div class="empty-state">No products matched the selected filters.</div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-6 col-xl-4">
                                <?php require __DIR__ . '/includes/product-card.php'; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <nav class="mt-5" aria-label="Product pagination">
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
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
