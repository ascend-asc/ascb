<?php
require_once '../config/config.php';
require_once '../app/core/Database.php';

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';

try {
    $db = Database::getInstance();
} catch (Exception $e) {
    // Database connection fallback
}

$url_parts = explode('/', $url);
$base_route = $url_parts[0];

switch ($base_route) {
    case 'home':
    case '':
        require_once 'views/home.php';
        break;
    case 'about':
        require_once 'views/about.php';
        break;
    case 'academics':
        require_once 'views/academics.php';
        break;
    case 'admissions':
        require_once 'views/admissions.php';
        break;
    case 'news':
        if (isset($url_parts[1]) && !empty($url_parts[1])) {
            $news_slug = $url_parts[1];
            require_once 'views/news_single.php';
        } else {
            require_once 'views/news.php';
        }
        break;
    case 'contact':
        require_once 'views/contact.php';
        break;
    case 'scholarships':
        require_once 'views/scholarships.php';
        break;
    case 'student-life':
        require_once 'views/student_life.php';
        break;
    case 'policies':
        require_once 'views/policies.php';
        break;
    case 'alumni':
        require_once 'views/alumni.php';
        break;
    case 'inquire':
        require_once 'views/inquire.php';
        break;
    default:
        // Try to find a matching published page in the Pages Manager
        $dynamic_page = null;
        if (isset($db)) {
            try {
                $db->query('SELECT * FROM pages WHERE slug = :slug AND is_published = 1');
                $db->bind(':slug', $base_route);
                $dynamic_page = $db->single();
            } catch (Exception $e) {
                // table may not exist
            }
        }

        if ($dynamic_page) {
            $page_title = htmlspecialchars($dynamic_page->title);
            $page_description = isset($dynamic_page->meta_description) ? htmlspecialchars($dynamic_page->meta_description) : '';
            require_once 'views/partials/header.php';
            echo '<main id="main-content">';
            echo '<div class="page-hero text-white"><div class="container text-center py-4"><h1 class="display-5 fw-bold">' . htmlspecialchars($dynamic_page->title) . '</h1></div></div>';
            echo '<div class="container py-5" style="max-width:900px;">' . $dynamic_page->body . '</div>';
            require_once 'views/partials/footer.php';
        } else {
            $page_title = '404 Not Found';
            $page_description = 'The page you are looking for does not exist.';
            require_once 'views/partials/header.php';
            echo '<main id="main-content">';
            echo '<div class="container text-center py-5 my-5"><h1>404 Not Found</h1><p>The page you are looking for does not exist.</p><a href="' . URLROOT . '" class="btn btn-primary">Return Home</a></div>';
            require_once 'views/partials/footer.php';
        }
        break;
}

