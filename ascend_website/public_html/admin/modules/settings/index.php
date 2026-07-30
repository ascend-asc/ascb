<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';

Auth::requireLogin();
$db = Database::getInstance();

$message = '';

// Save settings
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } elseif (isset($_POST['action']) && $_POST['action'] == 'save_settings') {
        $fields = ['site_name','site_tagline','contact_email','contact_phone','contact_address','facebook_url','maintenance_mode'];
        foreach ($fields as $key) {
            $value = isset($_POST[$key]) ? trim($_POST[$key]) : '';
            $db->query('INSERT INTO site_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v2');
            $db->bind(':k', $key);
            $db->bind(':v', $value);
            $db->bind(':v2', $value);
            $db->execute();
        }
        $message = '<div class="alert alert-success"><i data-lucide="check-circle" class="me-2" style="width:16px;height:16px;"></i> Settings saved successfully!</div>';
    } elseif (isset($_POST['action']) && $_POST['action'] == 'change_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_SESSION['user']->id;

        if ($new_password !== $confirm_password) {
            $message = '<div class="alert alert-danger">New passwords do not match.</div>';
        } elseif (strlen($new_password) < 6) {
            $message = '<div class="alert alert-danger">New password must be at least 6 characters long.</div>';
        } else {
            $db->query('SELECT password_hash FROM admin_users WHERE id = :id');
            $db->bind(':id', $user_id);
            $user = $db->single();

            if ($user && password_verify($current_password, $user->password_hash)) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $db->query('UPDATE admin_users SET password_hash = :hash WHERE id = :id');
                $db->bind(':hash', $new_hash);
                $db->bind(':id', $user_id);
                $db->execute();
                $message = '<div class="alert alert-success"><i data-lucide="check-circle" class="me-2" style="width:16px;height:16px;"></i> Password changed successfully!</div>';
            } else {
                $message = '<div class="alert alert-danger">Current password is incorrect.</div>';
            }
        }
    }
}

// Load current settings
$db->query('SELECT setting_key, setting_value FROM site_settings');
$rows = $db->resultSet();
$settings = [];
foreach ($rows as $row) {
    $settings[$row->setting_key] = $row->setting_value;
}

$s = function($key, $default = '') use ($settings) {
    return htmlspecialchars($settings[$key] ?? $default);
};

$page_title = 'Settings';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>

<main class="main-content" id="mainContent">
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="settings" class="me-2 text-primary"></i> Settings</h4>
    </div>

    <?php echo $message; ?>

    <div class="row g-4">
        <!-- Site Settings -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
                    <i data-lucide="globe" style="width:18px;height:18px;" class="text-primary"></i> General Site Settings
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <?php echo Csrf::getField(); ?>
                        <input type="hidden" name="action" value="save_settings">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">School / Site Name</label>
                                <input type="text" class="form-control" name="site_name" value="<?php echo $s('site_name','Andres Soriano Colleges of Bislig'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tagline</label>
                                <input type="text" class="form-control" name="site_tagline" value="<?php echo $s('site_tagline','Ascending to Excellence'); ?>">
                            </div>
                        </div>

                        <hr>
                        <h6 class="text-muted mb-3">Contact Information</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contact Email</label>
                                <input type="email" class="form-control" name="contact_email" value="<?php echo $s('contact_email','info@ascb.edu.ph'); ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" name="contact_phone" value="<?php echo $s('contact_phone','(086) 853-2222'); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">School Address</label>
                            <input type="text" class="form-control" name="contact_address" value="<?php echo $s('contact_address','Andres Soriano Ave, Mangagoy, Bislig City'); ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Facebook Page URL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i data-lucide="facebook" style="width:16px;height:16px;color:#1877F2;fill:#1877F2;"></i></span>
                                <input type="url" class="form-control" name="facebook_url" value="<?php echo $s('facebook_url','https://www.facebook.com/AndresSorianoCollege'); ?>" placeholder="https://www.facebook.com/YourPage">
                            </div>
                        </div>

                        <hr>

                        <div class="form-check form-switch mt-3 mb-4">
                            <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1" id="maintenance_mode" <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="maintenance_mode">
                                <strong>Maintenance Mode</strong>
                                <small class="text-muted d-block">When enabled, visitors will see a "Coming Soon" message instead of the website.</small>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i data-lucide="save" class="me-2" style="width:16px;height:16px;"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Change Password -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
                    <i data-lucide="lock" style="width:18px;height:18px;" class="text-warning"></i> Change My Password
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <?php echo Csrf::getField(); ?>
                        <input type="hidden" name="action" value="change_password">
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">
                            <i data-lucide="lock" class="me-2" style="width:16px;height:16px;"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- System Info -->
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
                    <i data-lucide="info" style="width:18px;height:18px;" class="text-info"></i> System Info
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tr><td class="ps-3 text-muted">PHP Version</td><td><strong><?php echo phpversion(); ?></strong></td></tr>
                        <tr><td class="ps-3 text-muted">Server</td><td><strong><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></strong></td></tr>
                        <tr><td class="ps-3 text-muted">Database</td><td><strong>MySQL</strong></td></tr>
                        <tr><td class="ps-3 text-muted">Logged in as</td><td><strong><?php echo htmlspecialchars($_SESSION['admin_full_name']); ?></strong></td></tr>
                        <tr><td class="ps-3 text-muted">Role</td><td><span class="badge bg-dark text-capitalize"><?php echo htmlspecialchars($_SESSION['admin_role'] ?? 'admin'); ?></span></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
<script>lucide.createIcons();</script>
