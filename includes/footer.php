<?php require_once __DIR__ . '/functions.php'; ?>
</main>

<footer class="site-footer mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 fw-bold mb-3">
                    <span class="brand-mark"><i class="bi bi-cpu"></i></span>
                    <span>Peripheral IMS</span>
                </div>
                <p class="text-secondary mb-0">A complete inventory and shopping platform for computer lab peripheral devices, built for fast operations and clean purchasing workflows.</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Explore</h6>
                <a href="<?= url('products.php') ?>">Products</a>
                <a href="<?= url('categories.php') ?>">Categories</a>
                <a href="<?= url('cart/index.php') ?>">Cart</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Company</h6>
                <a href="<?= url('about.php') ?>">About</a>
                <a href="<?= url('contact.php') ?>">Contact</a>
                <a href="<?= url('auth/login.php') ?>">Account</a>
            </div>
            <div class="col-lg-3">
                <h6>Lab Stock Alerts</h6>
                <p class="small text-secondary">Admins receive low-stock signals directly in the dashboard.</p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>
