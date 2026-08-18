<?php
require_once __DIR__ . '/../../app/core/Security.php';
// We expect $news_slug to be set by the router
$db->query('SELECT * FROM news_events WHERE slug = :slug AND status = "published"');
$db->bind(':slug', $news_slug);
$post = $db->single();

if (!$post) {
    // Post not found or not published
    require_once __DIR__ . '/partials/header.php';
    echo '<div class="container text-center py-5 my-5">
            <h1 class="display-4">404</h1>
            <h2>Post Not Found</h2>
            <p>The news article you are looking for does not exist or has been removed.</p>
            <a href="' . URLROOT . '/news" class="btn btn-primary mt-3">Back to News</a>
          </div>';
    require_once __DIR__ . '/partials/footer.php';
    exit;
}

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-hero text-white" style="background: linear-gradient(rgba(11, 47, 107, 0.85), rgba(11, 47, 107, 0.85))<?php echo $post->cover_image ? ', url(' . URLROOT . '/' . htmlspecialchars($post->cover_image) . ') no-repeat center center / cover' : ''; ?>;">
    <div class="container text-center py-5">
        <span class="badge bg-info text-dark mb-3 px-3 py-2 fs-6"><?php echo htmlspecialchars($post->category); ?></span>
        <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($post->title); ?></h1>
        <p class="text-white-50 mb-0"><i data-lucide="calendar" class="me-1"></i> Published on <?php echo date('F j, Y', strtotime($post->published_at)); ?></p>
    </div>
</div>

<div class="container py-5 max-w-4xl">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <a href="<?php echo URLROOT; ?>/news" class="text-decoration-none text-muted mb-4 d-inline-block">
                <i data-lucide="arrow-left" class="align-text-bottom me-1" style="width:16px;height:16px;"></i> Back to all news
            </a>
            
            <div class="article-content mt-2" style="font-size: 1.1rem; line-height: 1.8; color: #333;">
                <?php 
                // Convert newlines to paragraphs for basic formatting if no HTML was provided
                $body = $post->body;
                if (strip_tags($body) === $body) {
                    echo nl2br(htmlspecialchars($body));
                } else {
                    // Output raw HTML if they used an HTML editor
                    echo Security::sanitizeHtml($body);
                }
                ?>
            </div>
            
            <hr class="my-5">
            
            <div class="d-flex justify-content-between align-items-center">
                <p class="mb-0 fw-bold">Share this article:</p>
                <div>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-sm btn-outline-primary me-2">Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post->title); ?>" target="_blank" class="btn btn-sm btn-outline-info">Twitter</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .max-w-4xl { max-width: 900px; }
    .article-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
</style>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
