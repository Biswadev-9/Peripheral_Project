<?php
require_once __DIR__ . '/../includes/functions.php';
$mode = $_GET['mode'] ?? '';
if ($mode === 'cart') {
    unset($_SESSION['buy_now']);
}

$isBuyNow = !empty($_SESSION['buy_now']['product_id']) && $mode !== 'cart';
$items = [];

if ($isBuyNow) {
    $stmt = db()->prepare('SELECT p.id AS product_id, p.name, p.brand, p.model, p.price, p.stock_quantity, p.image_url, p.slug FROM products p WHERE p.id = ? AND p.status = "Available"');
    $stmt->execute([(int) $_SESSION['buy_now']['product_id']]);
    $buyNowProduct = $stmt->fetch();

    if ($buyNowProduct) {
        $buyNowProduct['quantity'] = min(max(1, (int) $_SESSION['buy_now']['quantity']), (int) $buyNowProduct['stock_quantity']);
        $items = [$buyNowProduct];
    }
} else {
    $items = array_values(array_filter(cart_items(), fn ($item) => !(bool) $item['saved_for_later']));
}

if (!$items) {
    unset($_SESSION['buy_now']);
    flash('warning', $isBuyNow ? 'The selected product is not available for checkout.' : 'Your cart is empty.');
    redirect($isBuyNow ? 'products.php' : 'cart/index.php');
}

$discount = 0.0;
$baseSubtotal = array_reduce($items, fn ($sum, $item) => $sum + ((float) $item['price'] * (int) $item['quantity']), 0.0);
if (!empty($_SESSION['coupon']['rate'])) {
    $discount = $baseSubtotal * (float) $_SESSION['coupon']['rate'];
}
$totals = cart_totals($items, $discount);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? 'Cash on Delivery';

    if ($fullName === '' || $email === '' || $phone === '' || $address === '') {
        flash('danger', 'Please complete all checkout fields.');
        redirect('checkout/index.php');
    }

    db()->beginTransaction();
    try {
        $invoiceNo = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $stmt = db()->prepare('INSERT INTO orders (user_id, invoice_no, full_name, email, phone, address, payment_method, subtotal, tax, discount, shipping, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            current_user()['id'] ?? null,
            $invoiceNo,
            $fullName,
            $email,
            $phone,
            $address,
            $paymentMethod,
            $totals['subtotal'],
            $totals['tax'],
            $totals['discount'],
            $totals['shipping'],
            $totals['grandTotal'],
        ]);
        $orderId = (int) db()->lastInsertId();

        $itemStmt = db()->prepare('INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity, total) VALUES (?, ?, ?, ?, ?, ?)');
        $stockStmt = db()->prepare('UPDATE products SET stock_quantity = GREATEST(stock_quantity - ?, 0), status = IF(stock_quantity - ? <= 0, "Out of Stock", status) WHERE id = ?');
        foreach ($items as $item) {
            $lineTotal = (float) $item['price'] * (int) $item['quantity'];
            $itemStmt->execute([$orderId, $item['product_id'], $item['name'], $item['price'], $item['quantity'], $lineTotal]);
            $stockStmt->execute([$item['quantity'], $item['quantity'], $item['product_id']]);
        }

        $payment = db()->prepare('INSERT INTO payments (order_id, payment_method, amount, status, transaction_ref) VALUES (?, ?, ?, ?, ?)');
        $payment->execute([$orderId, $paymentMethod, $totals['grandTotal'], $paymentMethod === 'Cash on Delivery' ? 'Pending' : 'Paid', 'TXN-' . strtoupper(bin2hex(random_bytes(4)))]);

        if ($isBuyNow) {
            unset($_SESSION['buy_now']);
        } else {
            $cartId = get_active_cart_id();
            db()->prepare('UPDATE carts SET status = "converted" WHERE id = ?')->execute([$cartId]);
        }
        unset($_SESSION['coupon']);
        db()->commit();

        flash('success', 'Order placed successfully. Your invoice is ready.');
        redirect('checkout/invoice.php?id=' . $orderId);
    } catch (Throwable $exception) {
        db()->rollBack();
        flash('danger', 'Could not place order: ' . $exception->getMessage());
        redirect('checkout/index.php');
    }
}

$user = current_user();
$pageTitle = 'Checkout | Peripheral IMS';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <form class="soft-card p-4" method="post">
                    <?= csrf_field() ?>
                    <span class="eyebrow">Checkout</span>
                    <h1 class="fw-bold mt-2 mb-4">Delivery and payment</h1>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Full Name</label><input class="form-control" name="full_name" value="<?= e($user['name'] ?? '') ?>" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required></div>
                        <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" required></div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method</label>
                            <select class="form-select" name="payment_method">
                                <option>Cash on Delivery</option>
                                <option>Credit Card</option>
                                <option>Debit Card</option>
                                <option>Mobile Banking</option>
                            </select>
                        </div>
                        <div class="col-12"><label class="form-label">Address</label><textarea class="form-control" name="address" rows="4" required></textarea></div>
                        <div class="col-12"><button class="btn btn-primary btn-lg" type="submit">Place Order</button></div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5">
                <div class="soft-card p-4">
                    <h4 class="fw-bold">Order Summary</h4>
                    <?php if ($isBuyNow): ?>
                        <div class="alert alert-info small">Buy Now checkout includes only the selected product. Your cart stays unchanged.</div>
                    <?php endif; ?>
                    <?php foreach ($items as $item): ?>
                        <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
                            <span><?= e($item['name']) ?> × <?= (int) $item['quantity'] ?></span>
                            <strong><?= money((float) $item['price'] * (int) $item['quantity']) ?></strong>
                        </div>
                    <?php endforeach; ?>
                    <div class="d-flex justify-content-between py-2"><span>Subtotal</span><strong><?= money($totals['subtotal']) ?></strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Tax</span><strong><?= money($totals['tax']) ?></strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Discount</span><strong>-<?= money($totals['discount']) ?></strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Shipping</span><strong><?= money($totals['shipping']) ?></strong></div>
                    <hr>
                    <div class="d-flex justify-content-between fs-5"><strong>Total</strong><strong><?= money($totals['grandTotal']) ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
