<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';

Auth::requireLogin();
$db = Database::getInstance();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'mark_read') {
    if (isset($_POST['csrf_token']) && Csrf::validateToken($_POST['csrf_token'])) {
        $db->query('UPDATE inquiries SET is_read = 1 WHERE id = :id');
        $db->bind(':id', (int) ($_POST['inquiry_id'] ?? 0));
        $db->execute();
        $message = '<div class="alert alert-success">Marked as read.</div>';
    }
}

$db->query('SELECT * FROM inquiries ORDER BY submitted_at DESC');
$inquiries = $db->resultSet();
?>
<?php
$page_title = 'Inquiries Inbox';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="mail"></i> Inquiries Inbox</h4>
        </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Sender</th>
                        <th>Email & Phone</th>
                        <th>Type</th>
                        <th>Message Snippet</th>
                        <th>Submitted</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($inquiries)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No inquiries received yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach($inquiries as $inq): ?>
                    <tr class="<?php echo $inq->is_read ? '' : 'fw-bold table-warning'; ?>">
                        <td class="ps-4"><?php echo htmlspecialchars($inq->name); ?></td>
                        <td>
                            <small>
                                <i data-lucide="mail"></i> <?php echo htmlspecialchars($inq->email); ?><br>
                                <i data-lucide="phone"></i> <?php echo htmlspecialchars($inq->phone ?? 'N/A'); ?>
                            </small>
                        </td>
                        <td><span class="badge bg-primary"><?php echo htmlspecialchars($inq->form_type ?? 'General'); ?></span></td>
                        <td><span class="text-truncate d-inline-block" style="max-width: 250px;"><?php echo htmlspecialchars($inq->message); ?></span></td>
                        <td><small class="text-muted"><?php echo date('M d, Y H:i', strtotime($inq->submitted_at)); ?></small></td>
                        <td class="text-end pe-4">
                            <?php if(!$inq->is_read): ?>
                                <form action="" method="POST" class="d-inline">
                                    <?php echo Csrf::getField(); ?>
                                    <input type="hidden" name="action" value="mark_read">
                                    <input type="hidden" name="inquiry_id" value="<?php echo $inq->id; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-check2"></i> Read</button>
                                </form>
                            <?php else: ?>
                                <span class="badge bg-light text-muted border">Read</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script></body>
</html>



