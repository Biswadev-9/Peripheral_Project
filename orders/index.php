<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
$stmt = db()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([current_user()['id']]);
$orders = $stmt->fetchAll();
$pageTitle = 'My Orders | Peripheral IMS';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-pad">
    <div class="container">
        <span class="eyebrow">Orders</span>
        <h1 class="fw-bold mt-2 mb-4">My Orders</h1>
        <?php if (!$orders): ?>
            <div class="empty-state">No orders yet.</div>
        <?php else: ?>
            <div class="table-responsive soft-card p-3">
                <table class="table mb-0">
                    <thead><tr><th>Invoice</th><th>Date</th><th>Status</th><th>Total</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= e($order['invoice_no']) ?></td>
                                <td><?= e(date('M j, Y', strtotime($order['created_at']))) ?></td>
                                <td><?= e($order['status']) ?></td>
                                <td><?= money($order['grand_total']) ?></td>
                                <td><a class="btn btn-sm btn-outline-primary" href="<?= url('checkout/invoice.php?id=' . (int) $order['id']) ?>">Invoice</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
