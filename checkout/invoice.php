<?php
require_once __DIR__ . '/../includes/functions.php';
$orderId = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM orders WHERE id = ?');
$stmt->execute([$orderId]);
$order = $stmt->fetch();
if (!$order) {
    http_response_code(404);
    exit('Invoice not found.');
}
$itemsStmt = db()->prepare('SELECT * FROM order_items WHERE order_id = ?');
$itemsStmt->execute([$orderId]);
$items = $itemsStmt->fetchAll();
$pageTitle = 'Invoice ' . $order['invoice_no'] . ' | Peripheral IMS';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-pad">
    <div class="container">
        <div class="d-flex justify-content-end mb-3 no-print">
            <button class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Print</button>
        </div>
        <div class="soft-card invoice-receipt p-4 p-lg-5 mx-auto">
            <div class="text-center">
                <h1 class="invoice-title">INVOICE</h1>
                <h2 class="invoice-number"><?= e($order['invoice_no']) ?></h2>
                <p class="mb-0">Date: <?= e(date('F j, Y', strtotime($order['created_at']))) ?></p>
            </div>

            <div class="invoice-separator"></div>

            <div class="invoice-block">
                <h3>BILL TO</h3>
                <p><strong>Name:</strong> <?= e($order['full_name']) ?></p>
                <p><strong>Email:</strong> <?= e($order['email']) ?></p>
                <p><strong>Phone:</strong> <?= e($order['phone']) ?></p>
                <p><strong>Address:</strong> <?= nl2br(e($order['address'])) ?></p>
            </div>

            <div class="invoice-separator"></div>

            <div class="invoice-block">
                <h3>ITEM DETAILS</h3>
                <div class="table-responsive">
                    <table class="table invoice-table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= e($item['product_name']) ?></td>
                                    <td class="text-center"><?= (int) $item['quantity'] ?></td>
                                    <td class="text-end"><?= money($item['unit_price']) ?></td>
                                    <td class="text-end"><?= money($item['total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="invoice-separator"></div>

            <div class="invoice-block">
                <h3>SUMMARY</h3>
                <div class="invoice-summary">
                    <div><span>Subtotal:</span><strong><?= money($order['subtotal']) ?></strong></div>
                    <div><span>Tax:</span><strong><?= money($order['tax']) ?></strong></div>
                    <div><span>Shipping:</span><strong><?= money($order['shipping']) ?></strong></div>
                    <div><span>Discount:</span><strong><?= money($order['discount']) ?></strong></div>
                </div>
            </div>

            <div class="invoice-separator"></div>

            <div class="invoice-total">
                <span>GRAND TOTAL:</span>
                <strong><?= money($order['grand_total']) ?></strong>
            </div>

            <div class="invoice-payment">
                <p><strong>Payment Method:</strong> <?= e($order['payment_method']) ?></p>
                <p><strong>Status:</strong> <?= e($order['status']) ?></p>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
