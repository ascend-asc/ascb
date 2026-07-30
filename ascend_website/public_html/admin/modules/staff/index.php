<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';

Auth::requireLogin();
$db = Database::getInstance();

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        if (isset($_POST['action']) && $_POST['action'] == 'save_staff') {
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
            $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);
            
            $photo = null;
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $new_filename = uniqid('staff_') . '.' . $ext;
                    $upload_path = '../../../../public_html/uploads/staff/' . $new_filename;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                        $photo = 'uploads/staff/' . $new_filename;
                    }
                }
            }

            $db->query('INSERT INTO staff_directory (name, position, department, photo) VALUES (:n, :p, :d, :ph)');
            $db->bind(':n', $name);
            $db->bind(':p', $position);
            $db->bind(':d', $department);
            $db->bind(':ph', $photo);
            
            if ($db->execute()) {
                $message = '<div class="alert alert-success">Staff member added.</div>';
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_staff') {
            $id = (int)$_POST['staff_id'];
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $position = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
            $department = filter_var($_POST['department'], FILTER_SANITIZE_STRING);

            // Keep existing photo unless a new one is uploaded
            $db->query('SELECT photo FROM staff_directory WHERE id = :id');
            $db->bind(':id', $id);
            $current = $db->single();
            $photo = $current ? $current->photo : null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $new_filename = uniqid('staff_') . '.' . $ext;
                    $upload_path = '../../../../public_html/uploads/staff/' . $new_filename;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                        // Delete old photo if exists
                        if ($current && $current->photo && file_exists('../../../../public_html/' . $current->photo)) {
                            unlink('../../../../public_html/' . $current->photo);
                        }
                        $photo = 'uploads/staff/' . $new_filename;
                    }
                }
            }

            $db->query('UPDATE staff_directory SET name=:n, position=:p, department=:d, photo=:ph WHERE id=:id');
            $db->bind(':n', $name);
            $db->bind(':p', $position);
            $db->bind(':d', $department);
            $db->bind(':ph', $photo);
            $db->bind(':id', $id);

            if ($db->execute()) {
                $message = '<div class="alert alert-success">Staff member updated.</div>';
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_staff') {
            $db->query('DELETE FROM staff_directory WHERE id = :id');
            $db->bind(':id', $_POST['staff_id']);
            $db->execute();
            $message = '<div class="alert alert-success">Staff member removed.</div>';
        }
    }
}

$db->query('SELECT * FROM staff_directory ORDER BY id DESC');
$staff = $db->resultSet();
?>
<?php
$page_title = 'Staff Directory';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<style>
.staff-avatar { width: 50px; height: 50px; object-fit: cover; border-radius: 50%; }
</style>
<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="users"></i> Staff Directory</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add Staff</button>
        </div>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Photo</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($staff)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">No staff entries found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($staff as $person): ?>
                    <tr>
                        <td class="ps-4">
                            <?php if($person->photo): ?>
                                <img src="<?php echo URLROOT; ?>/<?php echo htmlspecialchars($person->photo); ?>" class="staff-avatar">
                            <?php else: ?>
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center staff-avatar">
                                    <i data-lucide="user"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo htmlspecialchars($person->name); ?></strong></td>
                        <td><?php echo htmlspecialchars($person->position); ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($person->department); ?></span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary me-1" onclick='editStaff(<?php echo json_encode($person); ?>)'>
                                <i data-lucide="pencil" style="width:14px;height:14px;"></i>
                            </button>
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                                <?php echo Csrf::getField(); ?>
                                <input type="hidden" name="action" value="delete_staff">
                                <input type="hidden" name="staff_id" value="<?php echo $person->id; ?>">
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

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Faculty / Staff</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="save_staff">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" name="name" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Position / Designation</label>
                  <input type="text" class="form-control" name="position" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Department / Office</label>
                  <input type="text" class="form-control" name="department" placeholder="e.g. Administration, College Faculty">
              </div>
              <div class="mb-3">
                  <label class="form-label">Photo</label>
                  <input type="file" class="form-control" name="photo" accept="image/*">
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Member</button>
          </div>
      </form>
    </div>
  </div>
</div>

</main>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Staff Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST" enctype="multipart/form-data">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="edit_staff">
          <input type="hidden" name="staff_id" id="edit_staff_id">
          <div class="modal-body">
              <div class="text-center mb-3">
                  <img id="edit_photo_preview" src="" class="staff-avatar" style="width:80px;height:80px;" onerror="this.style.display='none'">
              </div>
              <div class="mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" name="name" id="edit_staff_name" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Position / Designation</label>
                  <input type="text" class="form-control" name="position" id="edit_staff_position" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Department / Office</label>
                  <input type="text" class="form-control" name="department" id="edit_staff_department">
              </div>
              <div class="mb-3">
                  <label class="form-label">Update Photo</label>
                  <input type="file" class="form-control" name="photo" accept="image/*">
                  <small class="text-muted d-block mt-1">Leave blank to keep existing photo.</small>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Member</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
<script>
    lucide.createIcons();
    var URLROOT = '<?php echo URLROOT; ?>';
    function editStaff(person) {
        document.getElementById('edit_staff_id').value = person.id;
        document.getElementById('edit_staff_name').value = person.name;
        document.getElementById('edit_staff_position').value = person.position;
        document.getElementById('edit_staff_department').value = person.department;
        var preview = document.getElementById('edit_photo_preview');
        if (person.photo) {
            preview.src = URLROOT + '/' + person.photo;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
        var editModal = new bootstrap.Modal(document.getElementById('editStaffModal'));
        editModal.show();
    }
</script>
