<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

$id = (int) ($_GET['id'] ?? 0);
$product = null;
if ($id) {
    $stmt = db()->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        http_response_code(404);
        exit('Device not found.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $price = (float) ($_POST['price'] ?? 0);
    $interface = trim($_POST['interface_type'] ?? '');
    $status = $_POST['status'] ?? 'Available';
    $stock = (int) ($_POST['stock_quantity'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $imageUrl = trim($_POST['image_url'] ?? ($product['image_url'] ?? ''));
    $specLines = trim($_POST['specifications'] ?? '');
    $specs = [];
    foreach (preg_split('/\r\n|\r|\n/', $specLines) as $line) {
        if (str_contains($line, ':')) {
            [$key, $value] = array_map('trim', explode(':', $line, 2));
            if ($key !== '') {
                $specs[$key] = $value;
            }
        }
    }

    if (!empty($_FILES['image']['name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            $fileName = slugify($name) . '-' . time() . '.' . $extension;
            $target = __DIR__ . '/../uploads/' . $fileName;
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            $imageUrl = url('uploads/' . $fileName);
        }
    }

    $baseSlug = slugify($name);
    $slug = $product['slug'] ?? $baseSlug;

    if ($product) {
        $stmt = db()->prepare('UPDATE products SET category_id=?, name=?, slug=?, brand=?, model=?, price=?, interface_type=?, status=?, stock_quantity=?, description=?, specifications=?, image_url=?, gallery=?, updated_at=NOW() WHERE id=?');
        $stmt->execute([$categoryId, $name, $slug, $brand, $model, $price, $interface, $status, $stock, $description, json_encode($specs), $imageUrl, json_encode([$imageUrl]), $id]);
        flash('success', 'Device updated.');
    } else {
        $stmt = db()->prepare('INSERT INTO products (category_id, name, slug, brand, model, price, interface_type, status, stock_quantity, description, specifications, image_url, gallery) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$categoryId, $name, $slug, $brand, $model, $price, $interface, $status, $stock, $description, json_encode($specs), $imageUrl, json_encode([$imageUrl])]);
        flash('success', 'Device created.');
    }
    redirect('admin/devices.php');
}

$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$specText = '';
if ($product && $product['specifications']) {
    foreach ((json_decode($product['specifications'], true) ?: []) as $key => $value) {
        $specText .= $key . ': ' . $value . PHP_EOL;
    }
}
$pageTitle = ($product ? 'Edit Device' : 'Create Device') . ' | Peripheral IMS';
$activeAdmin = $product ? 'devices' : 'create';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
    <section class="admin-content">
        <div class="mb-4">
            <span class="eyebrow"><?= $product ? 'Update' : 'Create' ?></span>
            <h1 class="fw-bold mt-2"><?= $product ? 'Update Device' : 'Create Device' ?></h1>
        </div>
        <form class="soft-card p-4" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Device Name</label><input class="form-control" name="name" value="<?= e($product['name'] ?? '') ?>" required></div>
                <div class="col-md-6"><label class="form-label">Category</label><select class="form-select" name="category_id" required><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= ($product['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-4"><label class="form-label">Brand</label><input class="form-control" name="brand" value="<?= e($product['brand'] ?? '') ?>" required></div>
                <div class="col-md-4"><label class="form-label">Model</label><input class="form-control" name="model" value="<?= e($product['model'] ?? '') ?>" required></div>
                <div class="col-md-4"><label class="form-label">Price</label><input class="form-control" name="price" type="number" step="0.01" value="<?= e((string) ($product['price'] ?? '')) ?>" required></div>
                <div class="col-md-4"><label class="form-label">Interface</label><input class="form-control" name="interface_type" value="<?= e($product['interface_type'] ?? '') ?>" required></div>
                <div class="col-md-4"><label class="form-label">Status</label><select class="form-select" name="status"><?php foreach (['Available', 'Out of Stock', 'Under Maintenance'] as $status): ?><option <?= ($product['status'] ?? '') === $status ? 'selected' : '' ?>><?= e($status) ?></option><?php endforeach; ?></select></div>
                <div class="col-md-4"><label class="form-label">Stock Quantity</label><input class="form-control" name="stock_quantity" type="number" value="<?= e((string) ($product['stock_quantity'] ?? '0')) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Image URL</label><input class="form-control" name="image_url" value="<?= e($product['image_url'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label">Upload Image</label><input class="form-control" type="file" name="image" accept="image/png,image/jpeg,image/webp"></div>
                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4" required><?= e($product['description'] ?? '') ?></textarea></div>
                <div class="col-12"><label class="form-label">Specifications</label><textarea class="form-control" name="specifications" rows="5" placeholder="DPI: 1600&#10;Warranty: 1 year"><?= e($specText) ?></textarea></div>
                <div class="col-12"><button class="btn btn-primary" type="submit"><?= $product ? 'Save Changes' : 'Create Device' ?></button></div>
            </div>
        </form>
    </section>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
