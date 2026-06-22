<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'About | Peripheral IMS';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section-pad">
    <div class="container">
        <div class="soft-card p-4 p-lg-5">
            <span class="eyebrow">About</span>
            <h1 class="fw-bold mt-2">Built for computer laboratory operations.</h1>
            <p class="text-secondary fs-5 mb-4">Peripheral IMS combines inventory control and purchasing in one clean workflow. Lab admins can maintain stock, track low inventory, and review orders, while students or staff can browse, compare, wishlist, and purchase devices.</p>
            <div class="row g-4">
                <div class="col-md-4"><div class="spec-item h-100"><i class="bi bi-shield-check text-primary fs-3"></i><h5 class="mt-3">Secure Access</h5><p class="text-secondary mb-0">Role-based authentication separates customer and admin workflows.</p></div></div>
                <div class="col-md-4"><div class="spec-item h-100"><i class="bi bi-box-seam text-info fs-3"></i><h5 class="mt-3">Inventory CRUD</h5><p class="text-secondary mb-0">Create, update, delete, search, and filter peripheral device records.</p></div></div>
                <div class="col-md-4"><div class="spec-item h-100"><i class="bi bi-receipt text-success fs-3"></i><h5 class="mt-3">Order Invoices</h5><p class="text-secondary mb-0">Checkout generates invoice pages with tax, discount, shipping, and totals.</p></div></div>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
