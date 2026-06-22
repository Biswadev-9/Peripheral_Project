<?php
require_once __DIR__ . '/includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        flash('danger', 'Please complete all contact fields.');
        redirect('contact.php');
    }

    $stmt = db()->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $subject, $message]);
    flash('success', 'Thanks for reaching out. Your message has been recorded for the lab admin.');
    redirect('contact.php');
}
$pageTitle = 'Contact | Peripheral IMS';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <span class="eyebrow">Contact</span>
                <h1 class="fw-bold mt-2">Need support with lab peripherals?</h1>
                <p class="text-secondary">Send a message to the inventory team for purchasing, maintenance, or stock questions.</p>
                <div class="soft-card p-4">
                    <p class="mb-2"><i class="bi bi-envelope text-primary"></i> support@peripheral-ims.local</p>
                    <p class="mb-2"><i class="bi bi-telephone text-primary"></i> +880 1000 000 000</p>
                    <p class="mb-0"><i class="bi bi-geo-alt text-primary"></i> Computer Laboratory Office</p>
                </div>
            </div>
            <div class="col-lg-7">
                <form class="soft-card p-4" method="post">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                        <div class="col-12"><label class="form-label">Subject</label><input class="form-control" name="subject" required></div>
                        <div class="col-12"><label class="form-label">Message</label><textarea class="form-control" name="message" rows="6" required></textarea></div>
                        <div class="col-12"><button class="btn btn-primary" type="submit">Send Message</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
