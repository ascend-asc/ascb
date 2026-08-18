<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';
require_once '../../../../app/core/Upload.php';

Auth::requireLogin();
$db = Database::getInstance();

$message = '';
$public_root = __DIR__ . '/../../../../public_html';
$slides_directory = $public_root . '/uploads/slides';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        $action = $_POST['action'] ?? '';

        // ── ADD ──────────────────────────────────────────────────────────────
        if ($action == 'add_slide') {
            $title     = htmlspecialchars(trim($_POST['title']));
            $subtitle  = htmlspecialchars(trim($_POST['subtitle']));
            $cta_label = htmlspecialchars(trim($_POST['cta_label']));
            $cta_link  = htmlspecialchars(trim($_POST['cta_link']));

            $image_path = '';
            if (isset($_FILES['slide_image']) && $_FILES['slide_image']['error'] == 0) {
                try {
                    $new_filename = Upload::image($_FILES['slide_image'], $slides_directory, 'slide_');
                    $image_path = 'uploads/slides/' . $new_filename;
                } catch (RuntimeException $e) {
                    $message = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }

            if (empty($message) && $image_path) {
                $db->query('INSERT INTO hero_slides (title, subtitle, image_path, cta_label, cta_link, is_active) VALUES (:title, :subtitle, :image_path, :cta_label, :cta_link, 1)');
                $db->bind(':title', $title);
                $db->bind(':subtitle', $subtitle);
                $db->bind(':image_path', $image_path);
                $db->bind(':cta_label', $cta_label);
                $db->bind(':cta_link', $cta_link);
                $db->execute();
                $message = '<div class="alert alert-success"><i data-lucide="check-circle"></i>Slide added successfully.</div>';
            } elseif (empty($message)) {
                $message = '<div class="alert alert-danger">Please select an image to upload.</div>';
            }

        // ── EDIT ─────────────────────────────────────────────────────────────
        } elseif ($action == 'edit_slide') {
            $id        = (int)$_POST['slide_id'];
            $title     = htmlspecialchars(trim($_POST['title']));
            $subtitle  = htmlspecialchars(trim($_POST['subtitle']));
            $cta_label = htmlspecialchars(trim($_POST['cta_label']));
            $cta_link  = htmlspecialchars(trim($_POST['cta_link']));
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            // Fetch current slide to retain existing image if no new one uploaded
            $db->query('SELECT image_path FROM hero_slides WHERE id = :id');
            $db->bind(':id', $id);
            $current = $db->single();
            $image_path = $current ? $current->image_path : '';

            // Optional new image upload
            if (isset($_FILES['slide_image']) && $_FILES['slide_image']['error'] == 0) {
                try {
                    $new_filename = Upload::image($_FILES['slide_image'], $slides_directory, 'slide_');
                    if ($current && $current->image_path) {
                        Upload::deletePublicFile($current->image_path, $public_root, 'uploads/slides/');
                    }
                    $image_path = 'uploads/slides/' . $new_filename;
                } catch (RuntimeException $e) {
                    $message = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }

            if (empty($message)) {
                $db->query('UPDATE hero_slides SET title=:title, subtitle=:subtitle, image_path=:image_path, cta_label=:cta_label, cta_link=:cta_link, is_active=:is_active WHERE id=:id');
                $db->bind(':title', $title);
                $db->bind(':subtitle', $subtitle);
                $db->bind(':image_path', $image_path);
                $db->bind(':cta_label', $cta_label);
                $db->bind(':cta_link', $cta_link);
                $db->bind(':is_active', $is_active);
                $db->bind(':id', $id);
                $db->execute();
                $message = '<div class="alert alert-success"><i data-lucide="check-circle"></i>Slide updated successfully.</div>';
            }

        // ── DELETE ───────────────────────────────────────────────────────────
        } elseif ($action == 'delete_slide') {
            $id = (int)$_POST['slide_id'];
            $db->query('SELECT image_path FROM hero_slides WHERE id = :id');
            $db->bind(':id', $id);
            $slide = $db->single();
            if ($slide) {
                Upload::deletePublicFile($slide->image_path, $public_root, 'uploads/slides/');
                $db->query('DELETE FROM hero_slides WHERE id = :id');
                $db->bind(':id', $id);
                $db->execute();
                $message = '<div class="alert alert-success"><i data-lucide="check-circle"></i>Slide deleted.</div>';
            }
        }
    }
}

$db->query('SELECT * FROM hero_slides ORDER BY sort_order ASC, id DESC');
$slides = $db->resultSet();
?>
<?php
$page_title = 'Hero Slider Manager';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<style>
.slide-thumb { height: 70px; width: 120px; object-fit: cover; border-radius: 6px; border: 2px solid #dee2e6; }
</style>
<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="image"></i> Hero Slider Manager</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlideModal">
                <i data-lucide="plus"></i> Add New Slide
            </button>
        </div>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Image</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($slides)): ?>
                    <tr><td colspan="4" class="text-center py-4 text-muted">No slides found. Click "Add New Slide" to get started.</td></tr>
                    <?php endif; ?>
                    <?php foreach($slides as $slide): ?>
                    <tr>
                        <td class="ps-4">
                            <img src="<?php echo URLROOT . '/' . htmlspecialchars($slide->image_path); ?>" class="slide-thumb" alt="Slide">
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($slide->title); ?></strong><br>
                            <small class="text-muted"><?php echo htmlspecialchars($slide->subtitle); ?></small><br>
                            <?php if($slide->cta_label): ?>
                            <span class="badge bg-light text-dark border mt-1"><i data-lucide="link"></i> <?php echo htmlspecialchars($slide->cta_label); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($slide->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <!-- Edit Button -->
                            <button class="btn btn-sm btn-outline-primary me-1"
                                data-bs-toggle="modal" data-bs-target="#editSlideModal"
                                data-id="<?php echo $slide->id; ?>"
                                data-title="<?php echo htmlspecialchars($slide->title); ?>"
                                data-subtitle="<?php echo htmlspecialchars($slide->subtitle); ?>"
                                data-cta_label="<?php echo htmlspecialchars($slide->cta_label); ?>"
                                data-cta_link="<?php echo htmlspecialchars($slide->cta_link); ?>"
                                data-is_active="<?php echo $slide->is_active; ?>"
                                data-image="<?php echo URLROOT . '/' . htmlspecialchars($slide->image_path); ?>">
                                <i data-lucide="pencil"></i> Edit
                            </button>
                            <!-- Delete Button -->
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this slide permanently?');">
                                <?php echo Csrf::getField(); ?>
                                <input type="hidden" name="action" value="delete_slide">
                                <input type="hidden" name="slide_id" value="<?php echo $slide->id; ?>">
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

<!-- ── ADD SLIDE MODAL ──────────────────────────────────────── -->
<div class="modal fade" id="addSlideModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i data-lucide="plus-circle"></i>Add New Hero Slide</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="add_slide">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label fw-semibold">Background Image <span class="text-danger">*</span></label>
                  <input type="file" class="form-control" name="slide_image" accept="image/*" required>
                  <div class="form-text">Recommended: 1920×1080px (JPG/PNG/WEBP)</div>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Title (Main Heading)</label>
                  <input type="text" class="form-control" name="title" placeholder="e.g. Welcome to ASCB" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Subtitle</label>
                  <input type="text" class="form-control" name="subtitle" placeholder="e.g. Shaping competent professionals since 1952">
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Button Label <small class="text-muted">(Optional)</small></label>
                      <input type="text" class="form-control" name="cta_label" placeholder="e.g. Apply Now">
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Button Link <small class="text-muted">(Optional)</small></label>
                      <input type="text" class="form-control" name="cta_link" placeholder="e.g. /admissions">
                  </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary"><i data-lucide="save"></i> Save Slide</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- ── EDIT SLIDE MODAL ──────────────────────────────────────── -->
<div class="modal fade" id="editSlideModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-dark"><i data-lucide="pencil"></i>Edit Slide</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="edit_slide">
          <input type="hidden" name="slide_id" id="edit_slide_id">
          <div class="modal-body">
              <!-- Current Image Preview -->
              <div class="mb-3 text-center">
                  <p class="form-label fw-semibold mb-2">Current Image</p>
                  <img id="edit_image_preview" src="" alt="Current Slide Image" class="img-fluid rounded" style="max-height:180px; border:2px solid #dee2e6;">
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Replace Image <small class="text-muted">(Leave blank to keep current)</small></label>
                  <input type="file" class="form-control" name="slide_image" accept="image/*">
                  <div class="form-text">Recommended: 1920×1080px (JPG/PNG/WEBP)</div>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Title</label>
                  <input type="text" class="form-control" name="title" id="edit_title" required>
              </div>
              <div class="mb-3">
                  <label class="form-label fw-semibold">Subtitle</label>
                  <input type="text" class="form-control" name="subtitle" id="edit_subtitle">
              </div>
              <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Button Label</label>
                      <input type="text" class="form-control" name="cta_label" id="edit_cta_label" placeholder="e.g. Apply Now">
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label fw-semibold">Button Link</label>
                      <input type="text" class="form-control" name="cta_link" id="edit_cta_link" placeholder="e.g. /admissions">
                  </div>
              </div>
              <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                  <label class="form-check-label fw-semibold" for="edit_is_active">Slide is Active (visible on website)</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning text-dark fw-bold"><i data-lucide="save"></i> Save Changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

</main>
<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
<script>
// Populate Edit Modal with current slide data
document.getElementById('editSlideModal').addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    document.getElementById('edit_slide_id').value    = btn.dataset.id;
    document.getElementById('edit_title').value       = btn.dataset.title;
    document.getElementById('edit_subtitle').value    = btn.dataset.subtitle;
    document.getElementById('edit_cta_label').value   = btn.dataset.cta_label;
    document.getElementById('edit_cta_link').value    = btn.dataset.cta_link;
    document.getElementById('edit_image_preview').src = btn.dataset.image;
    document.getElementById('edit_is_active').checked = btn.dataset.is_active === '1';
});
</script>
