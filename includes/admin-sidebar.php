<?php
require_once __DIR__ . '/functions.php';
$activeAdmin = $activeAdmin ?? '';
?>
<aside class="admin-sidebar">
    <div class="d-flex align-items-center gap-2 fw-bold mb-4">
        <span class="brand-mark"><i class="bi bi-speedometer2"></i></span>
        <span>Admin Panel</span>
    </div>
    <a class="<?= $activeAdmin === 'dashboard' ? 'active' : '' ?>" href="<?= url('admin/index.php') ?>"><i class="bi bi-grid-1x2"></i> Dashboard</a>
    <a class="<?= $activeAdmin === 'devices' ? 'active' : '' ?>" href="<?= url('admin/devices.php') ?>"><i class="bi bi-hdd-stack"></i> Inventory</a>
    <a class="<?= $activeAdmin === 'orders' ? 'active' : '' ?>" href="<?= url('admin/orders.php') ?>"><i class="bi bi-receipt"></i> Orders</a>
    <a class="<?= $activeAdmin === 'create' ? 'active' : '' ?>" href="<?= url('admin/device-form.php') ?>"><i class="bi bi-plus-circle"></i> Create Device</a>
    <a href="<?= url('products.php') ?>"><i class="bi bi-shop"></i> Storefront</a>
</aside>
