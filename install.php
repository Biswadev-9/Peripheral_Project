<?php
declare(strict_types=1);

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;charset=utf8mb4', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $schema = file_get_contents(__DIR__ . '/database/schema.sql');
        $pdo->exec($schema);
        require_once __DIR__ . '/database/seed_products.php';
        seed_extra_products($pdo);
        require_once __DIR__ . '/database/product_image_assets.php';
        generate_product_image_assets($pdo, '');
        $message = 'Database installed successfully. You can now open the application.';
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Install Peripheral IMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: grid; place-items: center; background: #f8fafc; font-family: Inter, system-ui, sans-serif; }
        .card { max-width: 620px; border: 0; border-radius: 1rem; box-shadow: 0 24px 70px rgba(15,23,42,.12); }
    </style>
</head>
<body>
    <main class="card p-4 p-lg-5">
        <h1 class="fw-bold">Install Peripheral IMS</h1>
        <p class="text-secondary">This imports <code>database/schema.sql</code> into MySQL using the default XAMPP credentials: user <code>root</code> with an empty password.</p>
        <?php if ($message): ?><div class="alert alert-success"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        <form method="post" class="d-flex gap-2">
            <button class="btn btn-primary" type="submit">Install Database</button>
            <a class="btn btn-outline-primary" href="index.php">Open App</a>
        </form>
    </main>
</body>
</html>
