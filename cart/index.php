<?php
require_once __DIR__ . '/../includes/functions.php';
$allItems = cart_items();
$items = array_values(array_filter($allItems, fn ($item) => !(bool) $item['saved_for_later']));
$saved = array_values(array_filter($allItems, fn ($item) => (bool) $item['saved_for_later']));
$discount = 0.0;
$baseSubtotal = array_reduce($items, fn ($sum, $item) => $sum + ((float) $item['price'] * (int) $item['quantity']), 0.0);
if (!empty($_SESSION['coupon']['rate'])) {
    $discount = $baseSubtotal * (float) $_SESSION['coupon']['rate'];
}
$totals = cart_totals($items, $discount);
$pageTitle = 'Shopping Cart | Peripheral IMS';
require_once __DIR__ . '/../includes/header.php';
?>

<section class="section-pad">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="eyebrow">Cart</span>
                <h1 class="fw-bold mt-2 mb-0">Shopping Cart</h1>
            </div>
            <a class="btn btn-outline-primary" href="<?= url('products.php') ?>"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
        </div>

        <?php if (!$items && !$saved): ?>
            <div class="empty-state">Your cart is empty.</div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <form class="soft-card p-3 p-lg-4" method="post" action="<?= url('cart/actions.php') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="update">
                        <div class="vstack gap-3">
                            <?php foreach ($items as $item): ?>
                                <div class="d-flex flex-wrap align-items-center gap-3 border-bottom pb-3">
                                    <img src="<?= e(product_image($item['image_url'])) ?>" alt="<?= e($item['name']) ?>" class="rounded-3" style="width:96px;height:96px;object-fit:cover;">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><?= e($item['name']) ?></h5>
                                        <p class="small text-secondary mb-1"><?= e($item['brand']) ?> · <?= e($item['model']) ?></p>
                                        <strong><?= money($item['price']) ?></strong>
                                    </div>
                                    <input class="form-control" style="width:92px;" name="quantities[<?= (int) $item['id'] ?>]" value="<?= (int) $item['quantity'] ?>" inputmode="numeric">
                                    <strong><?= money((float) $item['price'] * (int) $item['quantity']) ?></strong>
                                    <button class="btn btn-outline-primary" name="action" value="save" formaction="<?= url('cart/actions.php') ?>" formmethod="post" onclick="this.form.item_id.value='<?= (int) $item['id'] ?>'">Save</button>
                                    <button class="btn btn-outline-danger" name="action" value="remove" formaction="<?= url('cart/actions.php') ?>" formmethod="post" onclick="this.form.item_id.value='<?= (int) $item['id'] ?>'">Remove</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="item_id" value="">
                        <?php if ($items): ?>
                            <button class="btn btn-primary mt-3" name="action" value="update" type="submit">Update Quantities</button>
                        <?php endif; ?>
                    </form>

                    <?php if ($saved): ?>
                        <div class="soft-card p-3 p-lg-4 mt-4">
                            <h4 class="fw-bold mb-3">Saved for later</h4>
                            <?php foreach ($saved as $item): ?>
                                <div class="d-flex flex-wrap align-items-center gap-3 border-bottom py-3">
                                    <img src="<?= e(product_image($item['image_url'])) ?>" alt="<?= e($item['name']) ?>" class="rounded-3" style="width:72px;height:72px;object-fit:cover;">
                                    <div class="flex-grow-1"><strong><?= e($item['name']) ?></strong><div class="text-secondary small"><?= money($item['price']) ?></div></div>
                                    <form method="post" action="<?= url('cart/actions.php') ?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="move_to_cart">
                                        <input type="hidden" name="item_id" value="<?= (int) $item['id'] ?>">
                                        <button class="btn btn-outline-primary">Move to cart</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4">
                    <div class="soft-card p-4 sticky-lg-top" style="top:6rem;">
                        <h4 class="fw-bold">Order Summary</h4>
                        <form class="d-flex gap-2 my-3" method="post" action="<?= url('cart/actions.php') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="coupon">
                            <input class="form-control" name="coupon" placeholder="Coupon code" value="<?= e($_SESSION['coupon']['code'] ?? '') ?>">
                            <button class="btn btn-outline-primary">Apply</button>
                        </form>
                        <div class="d-flex justify-content-between py-2"><span>Subtotal</span><strong><?= money($totals['subtotal']) ?></strong></div>
                        <div class="d-flex justify-content-between py-2"><span>Tax</span><strong><?= money($totals['tax']) ?></strong></div>
                        <div class="d-flex justify-content-between py-2"><span>Discount</span><strong>-<?= money($totals['discount']) ?></strong></div>
                        <div class="d-flex justify-content-between py-2"><span>Shipping</span><strong><?= money($totals['shipping']) ?></strong></div>
                        <hr>
                        <div class="d-flex justify-content-between fs-5"><strong>Grand Total</strong><strong><?= money($totals['grandTotal']) ?></strong></div>
                        <a class="btn btn-primary w-100 mt-4 <?= !$items ? 'disabled' : '' ?>" href="<?= url('checkout/index.php?mode=cart') ?>">Checkout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
