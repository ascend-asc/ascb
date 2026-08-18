<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';
require_once '../../../../app/core/Security.php';

Auth::requireLogin();
$db = Database::getInstance();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        if (isset($_POST['action']) && $_POST['action'] == 'save_page') {
            $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
            $slug = filter_var($_POST['slug'], FILTER_SANITIZE_STRING);
            $body = Security::sanitizeHtml($_POST['body'] ?? '');
            $meta_title = filter_var($_POST['meta_title'], FILTER_SANITIZE_STRING);
            $meta_description = filter_var($_POST['meta_description'], FILTER_SANITIZE_STRING);
            $is_published = isset($_POST['is_published']) ? 1 : 0;
            
            $id = $_POST['page_id'] ?? null;
            
            if ($id) {
                $db->query('UPDATE pages SET title=:t, slug=:s, body=:b, meta_title=:mt, meta_description=:md, is_published=:ip WHERE id=:id');
                $db->bind(':id', $id);
            } else {
                $db->query('INSERT INTO pages (title, slug, body, meta_title, meta_description, is_published) VALUES (:t, :s, :b, :mt, :md, :ip)');
            }
            $db->bind(':t', $title);
            $db->bind(':s', $slug);
            $db->bind(':b', $body);
            $db->bind(':mt', $meta_title);
            $db->bind(':md', $meta_description);
            $db->bind(':ip', $is_published);
            
            try {
                if ($db->execute()) {
                    $message = '<div class="alert alert-success">Page saved successfully.</div>';
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = '<div class="alert alert-danger">Error: That URL Slug (<strong>' . htmlspecialchars($_POST['slug']) . '</strong>) is already used by another page. Please choose a unique slug.</div>';
                } else {
                    error_log('Unable to save page: ' . $e->getMessage());
                    $message = '<div class="alert alert-danger">Unable to save the page.</div>';
                }
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_page') {
            $db->query('DELETE FROM pages WHERE id=:id');
            $db->bind(':id', $_POST['page_id']);
            $db->execute();
            $message = '<div class="alert alert-success">Page deleted successfully.</div>';
        }
    }
}

$db->query('SELECT * FROM pages ORDER BY title ASC');
$pages = $db->resultSet();
?>
<?php
$page_title = 'Pages Manager';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="file-text"></i> Pages Manager</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPageModal">Add New Page</button>
        </div>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>URL Slug</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pages)): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No pages found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($pages as $page): ?>
                    <tr>
                        <td class="ps-4"><strong><?php echo htmlspecialchars($page->title); ?></strong></td>
                        <td><code>/<?php echo htmlspecialchars($page->slug); ?></code></td>
                        <td>
                            <?php if($page->is_published): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary me-1" onclick='editPage(<?php echo json_encode($page); ?>)'>
                                <i data-lucide="pencil" style="width:14px;height:14px;"></i>
                            </button>
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?');">
                                <?php echo Csrf::getField(); ?>
                                <input type="hidden" name="action" value="delete_page">
                                <input type="hidden" name="page_id" value="<?php echo $page->id; ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Page Modal (Simplified) -->
<div class="modal fade" id="addPageModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="save_page">
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-8 mb-3">
                      <label class="form-label">Page Title</label>
                      <input type="text" class="form-control" name="title" required>
                  </div>
                  <div class="col-md-4 mb-3">
                      <label class="form-label">URL Slug</label>
                      <input type="text" class="form-control" name="slug" placeholder="e.g. about-us" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Page Content (HTML supported)</label>
                  <textarea class="form-control" name="body" rows="10"></textarea>
                  <!-- In production, initialize TinyMCE or CKEditor here -->
              </div>
              <hr>
              <h6>SEO Options</h6>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Meta Title</label>
                      <input type="text" class="form-control" name="meta_title">
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Meta Description</label>
                      <input type="text" class="form-control" name="meta_description">
                  </div>
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="is_published" id="is_published" checked>
                  <label class="form-check-label" for="is_published">Publish immediately</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Page</button>
          </div>
      </form>
    </div>
  </div>
</div>

</main>

<!-- Edit Page Modal -->
<div class="modal fade" id="editPageModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="save_page">
          <input type="hidden" name="page_id" id="edit_page_id">
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-8 mb-3">
                      <label class="form-label">Page Title</label>
                      <input type="text" class="form-control" name="title" id="edit_page_title" required>
                  </div>
                  <div class="col-md-4 mb-3">
                      <label class="form-label">URL Slug</label>
                      <input type="text" class="form-control" name="slug" id="edit_page_slug" required>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Page Content (HTML supported)</label>
                  <textarea class="form-control" name="body" id="edit_page_body" rows="10"></textarea>
              </div>
              <hr>
              <h6>SEO Options</h6>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Meta Title</label>
                      <input type="text" class="form-control" name="meta_title" id="edit_meta_title">
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Meta Description</label>
                      <input type="text" class="form-control" name="meta_description" id="edit_meta_description">
                  </div>
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="is_published" id="edit_is_published">
                  <label class="form-check-label" for="edit_is_published">Published</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Page</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
<script>
    lucide.createIcons();
    function editPage(page) {
        document.getElementById('edit_page_id').value = page.id;
        document.getElementById('edit_page_title').value = page.title;
        document.getElementById('edit_page_slug').value = page.slug;
        document.getElementById('edit_page_body').value = page.body;
        document.getElementById('edit_meta_title').value = page.meta_title || '';
        document.getElementById('edit_meta_description').value = page.meta_description || '';
        document.getElementById('edit_is_published').checked = page.is_published == 1;
        var editModal = new bootstrap.Modal(document.getElementById('editPageModal'));
        editModal.show();
    }
</script>
