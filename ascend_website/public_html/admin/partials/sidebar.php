<?php
$db = Database::getInstance();
$db->query('SELECT COUNT(*) as count FROM inquiries WHERE is_read = 0');
$unread_inquiries = $db->single()->count;
?>
<!-- ── SIDEBAR ── -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="<?php echo URLROOT; ?>/images/ascb-logo-transparent.png" alt="ASCB">
        <div class="brand-text">ASCB CMS<br><small style="font-size:0.7rem; font-weight:400; opacity:0.7;">Admin Panel</small></div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="<?php echo URLROOT; ?>/admin/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['REQUEST_URI'], 'modules') === false ? 'active' : ''; ?>" data-tooltip="Dashboard">
            <i data-lucide="layout-dashboard"></i>
            <span class="link-label">Dashboard</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/hero-slider/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'hero-slider') !== false ? 'active' : ''; ?>" data-tooltip="Hero Slider">
            <i data-lucide="image"></i>
            <span class="link-label">Hero Slider</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/identity/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'identity') !== false ? 'active' : ''; ?>" data-tooltip="Inst. Identity">
            <i data-lucide="building-2"></i>
            <span class="link-label">Inst. Identity</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/pages/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'pages') !== false ? 'active' : ''; ?>" data-tooltip="Pages">
            <i data-lucide="file-text"></i>
            <span class="link-label">Pages</span>
        </a>

        <div class="nav-section-label">Content</div>
        <a href="<?php echo URLROOT; ?>/admin/modules/news/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'news') !== false ? 'active' : ''; ?>" data-tooltip="News & Events">
            <i data-lucide="newspaper"></i>
            <span class="link-label">News & Events</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/programs/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'programs') !== false ? 'active' : ''; ?>" data-tooltip="Programs">
            <i data-lucide="graduation-cap"></i>
            <span class="link-label">Programs</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/staff/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'staff') !== false ? 'active' : ''; ?>" data-tooltip="Staff Directory">
            <i data-lucide="users"></i>
            <span class="link-label">Staff Directory</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/media/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'media') !== false ? 'active' : ''; ?>" data-tooltip="Media Library">
            <i data-lucide="folder"></i>
            <span class="link-label">Media Library</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/inquiries/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'inquiries') !== false ? 'active' : ''; ?>" data-tooltip="Inquiries" style="position:relative;">
            <i data-lucide="mail"></i>
            <span class="link-label">Inquiries</span>
            <?php if($unread_inquiries > 0): ?>
                <span class="badge-dot"><?php echo $unread_inquiries; ?></span>
            <?php endif; ?>
        </a>

        <?php if($_SESSION['admin_role'] == 'superadmin'): ?>
        <div class="nav-section-label">Admin</div>
        <a href="<?php echo URLROOT; ?>/admin/modules/users/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'users') !== false ? 'active' : ''; ?>" data-tooltip="Users">
            <i data-lucide="badge"></i>
            <span class="link-label">Users</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/modules/settings/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'settings') !== false ? 'active' : ''; ?>" data-tooltip="Settings">
            <i data-lucide="settings"></i>
            <span class="link-label">Settings</span>
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?php echo URLROOT; ?>/admin/logout.php" data-tooltip="Logout" style="color:rgba(255,100,100,0.85);">
            <i data-lucide="log-out"></i>
            <span class="link-label">Logout</span>
        </a>
    </div>
</aside>

<!-- ── TOPBAR ── -->
<header class="topbar" id="topbar">
    <button id="sidebarToggle" title="Toggle sidebar">
        <i data-lucide="panel-left-close" id="toggleIcon" style="width:20px;height:20px;"></i>
    </button>
    <span class="topbar-title"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></span>
    <div class="topbar-right">
        <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['admin_full_name']); ?></strong></span>
        <a href="<?php echo URLROOT; ?>/admin/logout.php" title="Logout" style="color:#64748b;">
            <i data-lucide="log-out" style="width:18px;height:18px;"></i>
        </a>
    </div>
</header>
