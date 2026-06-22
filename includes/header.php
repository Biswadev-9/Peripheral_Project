<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? APP_NAME;
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$appBase = rtrim(APP_URL, '/');
$isAdminArea = str_starts_with($scriptName, $appBase . '/admin/');
$isAdminAllowedPublicPage = str_ends_with($scriptName, '/checkout/invoice.php')
    || str_ends_with($scriptName, '/auth/logout.php');

if (is_admin() && !$isAdminArea && !$isAdminAllowedPublicPage) {
    redirect('admin/index.php');
}

$categoriesForNav = is_admin() ? [] : get_categories(6);
?>
<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= asset('css/styles.css') ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top app-navbar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="<?= url(is_admin() ? 'admin/index.php' : 'index.php') ?>">
            <span class="brand-mark"><i class="bi bi-cpu"></i></span>
            <span>Peripheral IMS</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <?php if (is_admin()): ?>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?= url('admin/index.php') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('admin/devices.php') ?>">Inventory</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('admin/orders.php') ?>">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('admin/messages.php') ?>">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('admin/device-form.php') ?>">Create Device</a></li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?= url('index.php') ?>">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?= url('categories.php') ?>" role="button" data-bs-toggle="dropdown">Categories</a>
                        <ul class="dropdown-menu border-0 shadow-lg">
                            <?php foreach ($categoriesForNav as $navCategory): ?>
                                <li><a class="dropdown-item" href="<?= url('category.php?slug=' . e($navCategory['slug'])) ?>"><?= e($navCategory['name']) ?></a></li>
                            <?php endforeach; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item fw-semibold" href="<?= url('categories.php') ?>">View all categories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('products.php') ?>">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('about.php') ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('contact.php') ?>">Contact</a></li>
                </ul>
            <?php endif; ?>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-icon" id="themeToggle" type="button" aria-label="Toggle dark mode"><i class="bi bi-moon-stars"></i></button>
                <?php if (!is_admin()): ?>
                    <a class="btn btn-icon position-relative" href="<?= url('cart/index.php') ?>" aria-label="Cart">
                        <i class="bi bi-bag"></i>
                        <span class="cart-badge"><?= cart_count() ?></span>
                    </a>
                <?php endif; ?>
                <?php if (is_admin()): ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <?= e(current_user()['name']) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                            <li><a class="dropdown-item" href="<?= url('admin/index.php') ?>">Admin Panel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= url('auth/logout.php') ?>">Logout</a></li>
                        </ul>
                    </div>
                <?php elseif (is_logged_in()): ?>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <?= e(current_user()['name']) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg">
                            <li><a class="dropdown-item" href="<?= url('orders/index.php') ?>">My Orders</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= url('auth/logout.php') ?>">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-primary" href="<?= url('auth/login.php') ?>">Login/Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main>
    <div class="container flash-stack">
        <?php foreach (flashes() as $flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
                <?= e($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>
    </div>
