<?php
$db->query('SELECT * FROM news_events WHERE status = "published" ORDER BY published_at DESC');
$news = $db->resultSet();

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">News & Announcements</h1>
        <p class="lead">Stay updated with the latest happenings at ASCB</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php if(empty($news)): ?>
            <p class="text-center text-muted">No news posts published yet.</p>
        <?php else: ?>
            <?php foreach($news as $item): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <?php if($item->cover_image): ?>
                        <img src="<?php echo URLROOT; ?>/<?php echo htmlspecialchars($item->cover_image); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i data-lucide="newspaper"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge bg-info text-dark mb-2"><?php echo htmlspecialchars($item->category); ?></span>
                        <h5 class="card-title fw-bold">
                            <a href="<?php echo URLROOT; ?>/news/<?php echo htmlspecialchars($item->slug); ?>" class="text-decoration-none text-primary">
                                <?php echo htmlspecialchars($item->title); ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars($item->excerpt); ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <small class="text-muted"><i data-lucide="calendar"></i> <?php echo date('M d, Y', strtotime($item->published_at)); ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>


