<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $stmt = db()->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute([$_POST['status'], (int) $_POST['id']]);
    flash('success', 'Order status updated.');
    redirect('admin/orders.php');
}

$orders = db()->query('SELECT * FROM orders ORDER BY created_at DESC')->fetchAll();
$pageTitle = 'Orders | Peripheral IMS';
$activeAdmin = 'orders';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
    <section class="admin-content">
        <span class="eyebrow">Orders</span>
        <h1 class="fw-bold mt-2 mb-4">Order Management</h1>
        <div class="table-responsive soft-card p-3">
            <table class="table mb-0">
                <thead><tr><th>Invoice</th><th>Customer</th><th>Payment</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= e($order['invoice_no']) ?></td>
                            <td><?= e($order['full_name']) ?><div class="small text-secondary"><?= e($order['email']) ?></div></td>
                            <td><?= e($order['payment_method']) ?></td>
                            <td><?= money($order['grand_total']) ?></td>
                            <td>
                                <form class="d-flex gap-2" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= (int) $order['id'] ?>">
                                    <select class="form-select form-select-sm" name="status">
                                        <?php foreach (['Pending', 'Processing', 'Completed', 'Cancelled'] as $status): ?>
                                            <option <?= $order['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </form>
                            </td>
                            <td><a class="btn btn-sm btn-primary" href="<?= url('checkout/invoice.php?id=' . (int) $order['id']) ?>">Invoice</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
