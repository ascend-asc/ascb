<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';
require_once '../../../../app/core/Security.php';
require_once '../../../../app/core/Upload.php';

Auth::requireLogin();
$db = Database::getInstance();

$message = '';
$public_root = __DIR__ . '/../../../../public_html';
$news_directory = $public_root . '/uploads/news';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        if (isset($_POST['action']) && $_POST['action'] == 'save_news') {
            $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
            $slug = filter_var($_POST['slug'], FILTER_SANITIZE_STRING);
            $excerpt = filter_var($_POST['excerpt'], FILTER_SANITIZE_STRING);
            $body = Security::sanitizeHtml($_POST['body'] ?? '');
            $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
            $status = $_POST['status'] == 'published' ? 'published' : 'draft';
            $published_at = ($status == 'published') ? date('Y-m-d H:i:s') : null;
            
            // Handle image
            $cover_image = null;
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                try {
                    $new_filename = Upload::image($_FILES['cover_image'], $news_directory, 'news_');
                    $cover_image = 'uploads/news/' . $new_filename;
                } catch (RuntimeException $e) {
                    $message = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }

            $db->query('INSERT INTO news_events (title, slug, excerpt, body, cover_image, category, status, published_at) VALUES (:t, :s, :e, :b, :c, :cat, :stat, :p)');
            $db->bind(':t', $title);
            $db->bind(':s', $slug);
            $db->bind(':e', $excerpt);
            $db->bind(':b', $body);
            $db->bind(':c', $cover_image);
            $db->bind(':cat', $category);
            $db->bind(':stat', $status);
            $db->bind(':p', $published_at);
            
            try {
                if (empty($message) && $db->execute()) {
                    $message = '<div class="alert alert-success">News post saved!</div>';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = '<div class="alert alert-danger">Error: That URL Slug is already in use by another post. Please choose a unique slug.</div>';
                } else {
                    error_log('Unable to save news: ' . $e->getMessage());
                    $message = '<div class="alert alert-danger">Unable to save the news post.</div>';
                }
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_news') {
            $id = (int)$_POST['news_id'];
            $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
            $slug = filter_var($_POST['slug'], FILTER_SANITIZE_STRING);
            $excerpt = filter_var($_POST['excerpt'], FILTER_SANITIZE_STRING);
            $body = Security::sanitizeHtml($_POST['body'] ?? '');
            $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
            $status = isset($_POST['status']) && $_POST['status'] == 'published' ? 'published' : 'draft';
            
            $db->query('SELECT cover_image, status, published_at FROM news_events WHERE id=:id');
            $db->bind(':id', $id);
            $current = $db->single();
            
            $cover_image = $current ? $current->cover_image : null;
            $published_at = $current ? $current->published_at : null;
            
            // Handle publish date
            if ($status == 'published' && (!$current || $current->status == 'draft')) {
                $published_at = date('Y-m-d H:i:s');
            }

            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
                try {
                    $new_filename = Upload::image($_FILES['cover_image'], $news_directory, 'news_');
                    if ($current && $current->cover_image) {
                        Upload::deletePublicFile($current->cover_image, $public_root, 'uploads/news/');
                    }
                    $cover_image = 'uploads/news/' . $new_filename;
                } catch (RuntimeException $e) {
                    $message = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }

            $db->query('UPDATE news_events SET title=:t, slug=:s, excerpt=:e, body=:b, cover_image=:c, category=:cat, status=:stat, published_at=:p WHERE id=:id');
            $db->bind(':t', $title);
            $db->bind(':s', $slug);
            $db->bind(':e', $excerpt);
            $db->bind(':b', $body);
            $db->bind(':c', $cover_image);
            $db->bind(':cat', $category);
            $db->bind(':stat', $status);
            $db->bind(':p', $published_at);
            $db->bind(':id', $id);
            
            try {
                if (empty($message) && $db->execute()) {
                    $message = '<div class="alert alert-success">News post updated!</div>';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = '<div class="alert alert-danger">Error: That URL Slug is already in use by another post. Please choose a unique slug.</div>';
                } else {
                    error_log('Unable to update news: ' . $e->getMessage());
                    $message = '<div class="alert alert-danger">Unable to update the news post.</div>';
                }
            }


        } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_news') {
            $db->query('DELETE FROM news_events WHERE id=:id');
            $db->bind(':id', $_POST['news_id']);
            $db->execute();
            $message = '<div class="alert alert-success">News post deleted.</div>';
        }
    }
}

$db->query('SELECT * FROM news_events ORDER BY id DESC');
$news = $db->resultSet();
?>
<?php
$page_title = 'News & Events';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="newspaper"></i> News & Events</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">Add Post</button>
        </div>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($news)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No news posts found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($news as $item): ?>
                    <tr>
                        <td class="ps-4"><strong><?php echo htmlspecialchars($item->title); ?></strong></td>
                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($item->category); ?></span></td>
                        <td>
                            <?php if($item->status == 'published'): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?php echo $item->published_at ? date('M d, Y', strtotime($item->published_at)) : '-'; ?></small></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary me-1" onclick='editNews(<?php echo json_encode($item); ?>)'><i data-lucide="pencil"></i></button>
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?');">
                                <?php echo Csrf::getField(); ?>
                                <input type="hidden" name="action" value="delete_news">
                                <input type="hidden" name="news_id" value="<?php echo $item->id; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i data-lucide="trash-2"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create News Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="save_news">
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Title</label>
                      <input type="text" class="form-control" name="title" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">URL Slug</label>
                      <input type="text" class="form-control" name="slug" required>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Category</label>
                      <select class="form-select" name="category">
                          <option value="Announcements">Announcements</option>
                          <option value="Events">Events</option>
                          <option value="Academics">Academics</option>
                      </select>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Cover Image</label>
                      <input type="file" class="form-control" name="cover_image" accept="image/*">
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Excerpt (Short summary)</label>
                  <textarea class="form-control" name="excerpt" rows="2"></textarea>
              </div>
              <div class="mb-3">
                  <label class="form-label">Full Content</label>
                  <textarea class="form-control" name="body" rows="8"></textarea>
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="status" value="published" id="status" checked>
                  <label class="form-check-label" for="status">Publish immediately</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Post</button>
          </div>
      </form>
    </div>
  </div>
</div>

</main>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit News Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="edit_news">
          <input type="hidden" name="news_id" id="edit_news_id">
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Title</label>
                      <input type="text" class="form-control" name="title" id="edit_title" required>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">URL Slug</label>
                      <input type="text" class="form-control" name="slug" id="edit_slug" required>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Category</label>
                      <select class="form-select" name="category" id="edit_category">
                          <option value="Announcements">Announcements</option>
                          <option value="Events">Events</option>
                          <option value="Academics">Academics</option>
                      </select>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Update Cover Image</label>
                      <input type="file" class="form-control" name="cover_image" accept="image/*">
                      <small class="text-muted d-block mt-1">Leave blank to keep existing image.</small>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Excerpt (Short summary)</label>
                  <textarea class="form-control" name="excerpt" id="edit_excerpt" rows="2"></textarea>
              </div>
              <div class="mb-3">
                  <label class="form-label">Full Content</label>
                  <textarea class="form-control" name="body" id="edit_body" rows="8"></textarea>
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="status" value="published" id="edit_status">
                  <label class="form-check-label" for="edit_status">Published</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Post</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
<script>
    lucide.createIcons();
    function editNews(item) {
        document.getElementById('edit_news_id').value = item.id;
        document.getElementById('edit_title').value = item.title;
        document.getElementById('edit_slug').value = item.slug;
        document.getElementById('edit_category').value = item.category;
        document.getElementById('edit_excerpt').value = item.excerpt;
        document.getElementById('edit_body').value = item.body;
        document.getElementById('edit_status').checked = item.status === 'published';
        
        var editModal = new bootstrap.Modal(document.getElementById('editNewsModal'));
        editModal.show();
    }
</script>
