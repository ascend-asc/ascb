<?php
require_once '../../config/config.php';
require_once '../../app/core/Database.php';
require_once '../../app/core/Auth.php';

Auth::requireLogin();

$db = Database::getInstance();

$db->query('SELECT COUNT(*) as count FROM inquiries WHERE is_read = 0');
$unread_inquiries = $db->single()->count;

$db->query('SELECT COUNT(*) as count FROM news_events WHERE status = "published"');
$published_news = $db->single()->count;

$db->query('SELECT COUNT(*) as count FROM hero_slides WHERE is_active = 1');
$active_slides = $db->single()->count;
?>
<?php
$page_title = 'Dashboard';
require_once 'partials/header.php';
require_once 'partials/sidebar.php';
?>

<!-- ── MAIN CONTENT ── -->
<main class="main-content" id="mainContent">

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-label">Unread Inquiries</div>
                <div class="stat-value"><?php echo $unread_inquiries; ?></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="stat-card" style="border-left-color:#1F4E9C;">
                <div class="stat-label">Published News</div>
                <div class="stat-value"><?php echo $published_news; ?></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="stat-card" style="border-left-color:#198754;">
                <div class="stat-label">Active Hero Slides</div>
                <div class="stat-value"><?php echo $active_slides; ?></div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <h6 class="fw-bold text-muted mb-3" style="font-size:0.8rem;text-transform:uppercase;letter-spacing:1px;">Quick Actions</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="modules/hero-slider/" class="btn btn-outline-primary btn-sm"><i data-lucide="plus" style="width:14px;height:14px;"></i> Add Slide</a>
                    <a href="modules/news/" class="btn btn-outline-primary btn-sm"><i data-lucide="plus" style="width:14px;height:14px;"></i> New Article</a>
                    <a href="modules/programs/" class="btn btn-outline-primary btn-sm"><i data-lucide="plus" style="width:14px;height:14px;"></i> Add Program</a>
                    <a href="modules/staff/" class="btn btn-outline-primary btn-sm"><i data-lucide="plus" style="width:14px;height:14px;"></i> Add Staff</a>
                    <a href="modules/inquiries/" class="btn btn-outline-warning btn-sm"><i data-lucide="mail" style="width:14px;height:14px;"></i> View Inquiries</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-3 shadow-sm">
        <div class="p-4 border-bottom">
            <h6 class="fw-bold mb-0">Recent Activity</h6>
        </div>
        <div class="p-4 text-muted">
            <p class="mb-0">Activity logging will be displayed here.</p>
        </div>
    </div>

</main>

<?php require_once 'partials/footer.php'; ?>

