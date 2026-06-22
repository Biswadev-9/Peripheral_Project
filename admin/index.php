<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$stats = [
    'devices' => (int) db()->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'categories' => (int) db()->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
    'orders' => (int) db()->query('SELECT COUNT(*) FROM orders')->fetchColumn(),
    'revenue' => (float) db()->query('SELECT COALESCE(SUM(grand_total), 0) FROM orders WHERE status <> "Cancelled"')->fetchColumn(),
    'low_stock' => (int) db()->query('SELECT COUNT(*) FROM products WHERE stock_quantity <= 10')->fetchColumn(),
];

$salesRows = db()->query("SELECT DATE_FORMAT(created_at, '%b') AS month, SUM(grand_total) AS total FROM orders GROUP BY YEAR(created_at), MONTH(created_at) ORDER BY MIN(created_at) LIMIT 12")->fetchAll();
$salesLabels = array_column($salesRows, 'month') ?: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$salesValues = array_map('floatval', array_column($salesRows, 'total') ?: [0, 0, 0, 0, 0, 0]);

$distributionRows = db()->query('SELECT c.name, COUNT(p.id) AS total FROM categories c LEFT JOIN products p ON p.category_id = c.id GROUP BY c.id ORDER BY total DESC LIMIT 6')->fetchAll();
$distributionLabels = array_column($distributionRows, 'name');
$distributionValues = array_map('intval', array_column($distributionRows, 'total'));

$lowStock = db()->query('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.stock_quantity <= 10 ORDER BY p.stock_quantity ASC LIMIT 8')->fetchAll();
$pageTitle = 'Admin Dashboard | Peripheral IMS';
$activeAdmin = 'dashboard';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
    <section class="admin-content">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="eyebrow">Dashboard</span>
                <h1 class="fw-bold mt-2 mb-0">Inventory Overview</h1>
            </div>
            <a class="btn btn-primary" href="<?= url('admin/device-form.php') ?>"><i class="bi bi-plus"></i> New Device</a>
        </div>
        <div class="row g-3 mb-4">
            <?php
            $cards = [
                ['Total Devices', $stats['devices'], 'bi-hdd-stack'],
                ['Total Categories', $stats['categories'], 'bi-layers'],
                ['Total Orders', $stats['orders'], 'bi-receipt'],
                ['Total Revenue', money($stats['revenue']), 'bi-cash-stack'],
                ['Low Stock Items', $stats['low_stock'], 'bi-exclamation-triangle'],
            ];
            foreach ($cards as $card):
            ?>
                <div class="col-sm-6 col-xl">
                    <div class="dashboard-card">
                        <span class="icon mb-3"><i class="bi <?= e($card[2]) ?>"></i></span>
                        <p class="text-secondary mb-1"><?= e($card[0]) ?></p>
                        <h3 class="fw-bold mb-0"><?= e((string) $card[1]) ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="soft-card p-4 h-100">
                    <h4 class="fw-bold">Monthly Sales</h4>
                    <canvas id="monthlySalesChart" data-labels='<?= e(json_encode($salesLabels)) ?>' data-values='<?= e(json_encode($salesValues)) ?>'></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="soft-card p-4 h-100">
                    <h4 class="fw-bold">Inventory Distribution</h4>
                    <canvas id="inventoryChart" data-labels='<?= e(json_encode($distributionLabels)) ?>' data-values='<?= e(json_encode($distributionValues)) ?>'></canvas>
                </div>
            </div>
        </div>
        <div class="soft-card p-4">
            <h4 class="fw-bold mb-3">Low Stock Items</h4>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Device</th><th>Category</th><th>Stock</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($lowStock as $item): ?>
                            <tr>
                                <td><?= e($item['name']) ?></td>
                                <td><?= e($item['category_name']) ?></td>
                                <td><?= (int) $item['stock_quantity'] ?></td>
                                <td><?= e($item['status']) ?></td>
                                <td><a class="btn btn-sm btn-outline-primary" href="<?= url('admin/device-form.php?id=' . (int) $item['id']) ?>">Edit</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
