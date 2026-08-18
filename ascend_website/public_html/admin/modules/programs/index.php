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
$programs_directory = $public_root . '/uploads/programs';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        if (isset($_POST['action']) && $_POST['action'] == 'save_program') {
            $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $brochure_pdf = null;
            if (isset($_FILES['brochure_pdf']) && $_FILES['brochure_pdf']['error'] == 0) {
                try {
                    $new_filename = Upload::pdf($_FILES['brochure_pdf'], $programs_directory, 'program_');
                    $brochure_pdf = 'uploads/programs/' . $new_filename;
                } catch (RuntimeException $e) {
                    $message = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }

            $db->query('INSERT INTO programs (department, name, description, brochure_pdf, is_active) VALUES (:d, :n, :desc, :b, :a)');
            $db->bind(':d', $department);
            $db->bind(':n', $name);
            $db->bind(':desc', $description);
            $db->bind(':b', $brochure_pdf);
            $db->bind(':a', $is_active);
            
            if (empty($message) && $db->execute()) {
                $message = '<div class="alert alert-success">Academic program added successfully.</div>';
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_program') {
            $id = (int)$_POST['program_id'];
            $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            // Keep existing brochure if none uploaded
            $db->query('SELECT brochure_pdf FROM programs WHERE id = :id');
            $db->bind(':id', $id);
            $current = $db->single();
            $brochure_pdf = $current ? $current->brochure_pdf : null;

            if (isset($_FILES['brochure_pdf']) && $_FILES['brochure_pdf']['error'] == 0) {
                try {
                    $new_filename = Upload::pdf($_FILES['brochure_pdf'], $programs_directory, 'program_');
                    if ($current && $current->brochure_pdf) {
                        Upload::deletePublicFile($current->brochure_pdf, $public_root, 'uploads/programs/');
                    }
                    $brochure_pdf = 'uploads/programs/' . $new_filename;
                } catch (RuntimeException $e) {
                    $message = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }

            $db->query('UPDATE programs SET department=:d, name=:n, description=:desc, brochure_pdf=:b, is_active=:a WHERE id=:id');
            $db->bind(':d', $department);
            $db->bind(':n', $name);
            $db->bind(':desc', $description);
            $db->bind(':b', $brochure_pdf);
            $db->bind(':a', $is_active);
            $db->bind(':id', $id);
            
            if (empty($message) && $db->execute()) {
                $message = '<div class="alert alert-success">Academic program updated successfully.</div>';
            }

        } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_program') {
            $db->query('DELETE FROM programs WHERE id = :id');
            $db->bind(':id', $_POST['program_id']);
            $db->execute();
            $message = '<div class="alert alert-success">Program deleted.</div>';
        }
    }
}

$db->query('SELECT * FROM programs ORDER BY department ASC, name ASC');
$programs = $db->resultSet();
?>
<?php
$page_title = 'Programs Manager';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="graduation-cap"></i> Academic Programs</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProgramModal">Add Program</button>
        </div>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Program Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Brochure</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($programs)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No academic programs offered yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach($programs as $prog): ?>
                    <tr>
                        <td class="ps-4"><strong><?php echo htmlspecialchars($prog->name); ?></strong></td>
                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($prog->department); ?></span></td>
                        <td>
                            <?php if($prog->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($prog->brochure_pdf): ?>
                                <a href="/<?php echo htmlspecialchars($prog->brochure_pdf); ?>" target="_blank" class="btn btn-sm btn-outline-danger"><i data-lucide="file-text"></i> PDF</a>
                            <?php else: ?>
                                <span class="text-muted small">None</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary me-1" onclick='editProgram(<?php echo json_encode($prog); ?>)'><i data-lucide="pencil"></i></button>
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this program?');">
                                <?php echo Csrf::getField(); ?>
                                <input type="hidden" name="action" value="delete_program">
                                <input type="hidden" name="program_id" value="<?php echo $prog->id; ?>">
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

<!-- Add Program Modal -->
<div class="modal fade" id="addProgramModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Academic Program</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="save_program">
          <div class="modal-body">
              <div class="row mb-3">
                  <div class="col-md-8">
                      <label class="form-label">Program Name</label>
                      <input type="text" class="form-control" name="name" placeholder="e.g. Bachelor of Science in Information Technology" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label">Department</label>
                      <select class="form-select" name="department">
                          <option value="College">College</option>
                          <option value="TVET">TESDA / TVET</option>
                          <option value="Basic Ed">Basic Education</option>
                      </select>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea class="form-control" name="description" rows="4"></textarea>
              </div>
              <div class="mb-3">
                  <label class="form-label">Brochure PDF (Optional)</label>
                  <input type="file" class="form-control" name="brochure_pdf" accept="application/pdf">
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                  <label class="form-check-label" for="is_active">Active & Visible</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Program</button>
          </div>
      </form>
    </div>
  </div>
</div>

</main>

<!-- Edit Program Modal -->
<div class="modal fade" id="editProgramModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Academic Program</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="edit_program">
          <input type="hidden" name="program_id" id="edit_program_id">
          <div class="modal-body">
              <div class="row mb-3">
                  <div class="col-md-8">
                      <label class="form-label">Program Name</label>
                      <input type="text" class="form-control" name="name" id="edit_name" required>
                  </div>
                  <div class="col-md-4">
                      <label class="form-label">Department</label>
                      <select class="form-select" name="department" id="edit_department">
                          <option value="College">College</option>
                          <option value="TVET">TESDA / TVET</option>
                          <option value="Basic Ed">Basic Education</option>
                      </select>
                  </div>
              </div>
              <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea class="form-control" name="description" id="edit_description" rows="4"></textarea>
              </div>
              <div class="mb-3">
                  <label class="form-label">Update Brochure PDF (Optional)</label>
                  <input type="file" class="form-control" name="brochure_pdf" accept="application/pdf">
                  <small class="text-muted d-block mt-1">Leave blank to keep existing brochure.</small>
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active">
                  <label class="form-check-label" for="edit_is_active">Active & Visible</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Program</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
<script>
    lucide.createIcons();
    function editProgram(prog) {
        document.getElementById('edit_program_id').value = prog.id;
        document.getElementById('edit_name').value = prog.name;
        document.getElementById('edit_department').value = prog.department;
        document.getElementById('edit_description').value = prog.description;
        document.getElementById('edit_is_active').checked = prog.is_active == 1;
        
        var editModal = new bootstrap.Modal(document.getElementById('editProgramModal'));
        editModal.show();
    }
</script>
