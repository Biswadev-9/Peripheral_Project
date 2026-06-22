<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $email === '' || strlen($password) < 6) {
        flash('danger', 'Please provide a name, email, and password with at least 6 characters.');
        redirect('auth/register.php');
    }

    try {
        $stmt = db()->prepare('INSERT INTO users (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, "customer")');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $phone, $address]);
        flash('success', 'Account created. Please sign in.');
        redirect('auth/login.php');
    } catch (PDOException $exception) {
        flash('danger', 'This email is already registered.');
        redirect('auth/register.php');
    }
}

$pageTitle = 'Register | Peripheral IMS';
require_once __DIR__ . '/../includes/header.php';
?>
<section class="section-pad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form class="soft-card p-4 p-lg-5" method="post">
                    <?= csrf_field() ?>
                    <span class="eyebrow">Create Account</span>
                    <h1 class="fw-bold mt-2 mb-4">Register</h1>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Full Name</label><input class="form-control" name="name" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" required></div>
                        <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
                        <div class="col-md-6"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
                        <div class="col-12"><label class="form-label">Address</label><textarea class="form-control" name="address" rows="3"></textarea></div>
                        <div class="col-12"><button class="btn btn-primary w-100" type="submit">Create Account</button></div>
                    </div>
                    <p class="text-secondary text-center mt-3 mb-0">Already registered? <a href="<?= url('auth/login.php') ?>">Sign in</a></p>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
