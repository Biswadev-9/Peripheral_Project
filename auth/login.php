<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? AND status = "active" LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        sync_guest_cart_to_user((int) $user['id']);
        flash('success', 'Welcome back, ' . $user['name'] . '.');
        redirect($user['role'] === 'admin' ? 'admin/index.php' : 'index.php');
    }

    flash('danger', 'Invalid email or password.');
    redirect('auth/login.php');
}

$pageTitle = 'Login | Peripheral IMS';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-pad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <form class="soft-card p-4 p-lg-5" method="post">
                    <?= csrf_field() ?>
                    <span class="eyebrow">Welcome Back</span>
                    <h1 class="fw-bold mt-2 mb-4">Sign in</h1>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" placeholder="admin@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control" type="password" name="password" placeholder="admin123" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                    <p class="text-secondary text-center mt-3 mb-0">New here? <a href="<?= url('auth/register.php') ?>">Create an account</a></p>
                    <div class="alert alert-info mt-4 mb-0 small">
                        Demo admin: admin@example.com / admin123<br>
                        Demo user: user@example.com / user123
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
