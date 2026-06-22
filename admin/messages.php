<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'mark_read') {
        $stmt = db()->prepare('UPDATE contact_messages SET status = "Read" WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'Message marked as read.');
    }

    if ($action === 'delete') {
        $stmt = db()->prepare('DELETE FROM contact_messages WHERE id = ?');
        $stmt->execute([$id]);
        flash('success', 'Message deleted.');
    }

    redirect('admin/messages.php');
}

$status = trim($_GET['status'] ?? '');
$search = trim($_GET['search'] ?? '');
$where = [];
$params = [];

if (in_array($status, ['Unread', 'Read'], true)) {
    $where[] = 'status = ?';
    $params[] = $status;
}

if ($search !== '') {
    $where[] = '(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$stmt = db()->prepare("SELECT * FROM contact_messages $whereSql ORDER BY created_at DESC");
$stmt->execute($params);
$messages = $stmt->fetchAll();
$unreadCount = (int) db()->query('SELECT COUNT(*) FROM contact_messages WHERE status = "Unread"')->fetchColumn();

$pageTitle = 'Messages | Peripheral IMS';
require_once __DIR__ . '/../includes/header.php';
?>
<div class="admin-shell">
    <section class="admin-content">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="eyebrow">Messages</span>
                <h1 class="fw-bold mt-2 mb-0">Contact Messages</h1>
                <p class="text-secondary mt-2 mb-0"><?= $unreadCount ?> unread messages from users and visitors.</p>
            </div>
        </div>

        <form class="soft-card p-3 p-lg-4 mb-4" method="get">
            <div class="row g-3 align-items-end">
                <div class="col-md-7">
                    <label class="form-label">Search messages</label>
                    <input class="form-control" name="search" value="<?= e($search) ?>" placeholder="Name, email, subject, or message">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">All messages</option>
                        <option value="Unread" <?= $status === 'Unread' ? 'selected' : '' ?>>Unread</option>
                        <option value="Read" <?= $status === 'Read' ? 'selected' : '' ?>>Read</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> Filter</button>
                </div>
            </div>
        </form>

        <?php if (!$messages): ?>
            <div class="empty-state">No contact messages found.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($messages as $message): ?>
                    <div class="col-lg-6">
                        <article class="soft-card p-4 h-100">
                            <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                <div>
                                    <span class="badge <?= $message['status'] === 'Unread' ? 'text-bg-primary' : 'text-bg-light' ?> mb-2"><?= e($message['status']) ?></span>
                                    <h4 class="fw-bold mb-1"><?= e($message['subject']) ?></h4>
                                    <p class="small text-secondary mb-0"><?= e(date('M j, Y g:i A', strtotime($message['created_at']))) ?></p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong><?= e($message['name']) ?></strong>
                                <div><a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a></div>
                            </div>
                            <p class="text-secondary"><?= nl2br(e($message['message'])) ?></p>
                            <div class="d-flex flex-wrap gap-2">
                                <?php if ($message['status'] === 'Unread'): ?>
                                    <form method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="action" value="mark_read">
                                        <input type="hidden" name="id" value="<?= (int) $message['id'] ?>">
                                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-check2-circle"></i> Mark Read</button>
                                    </form>
                                <?php endif; ?>
                                <form method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $message['id'] ?>">
                                    <button class="btn btn-outline-danger" data-confirm="Delete this message?"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
