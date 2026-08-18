<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';

Auth::requireLogin();
Auth::requireRole('superadmin');
$db = Database::getInstance();

$message = '';
$current_user_id = Auth::currentUserId();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        if (isset($_POST['action']) && $_POST['action'] == 'save_user') {
            $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $role = in_array($_POST['role'] ?? '', ['superadmin', 'editor'], true) ? $_POST['role'] : 'editor';
            $status = isset($_POST['status']) && $_POST['status'] == 'active' ? 'active' : 'inactive';
            $password = $_POST['password'];
            
            if (!$email) {
                $message = '<div class="alert alert-danger">A valid email address is required.</div>';
            } elseif (strlen($password) < 12) {
                $message = '<div class="alert alert-danger">Password must be at least 12 characters.</div>';
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                try {
                    $db->query('INSERT INTO admin_users (full_name, email, password_hash, role, status) VALUES (:name, :email, :pass, :role, :status)');
                    $db->bind(':name', $full_name);
                    $db->bind(':email', $email);
                    $db->bind(':pass', $password_hash);
                    $db->bind(':role', $role);
                    $db->bind(':status', $status);
                    
                    if ($db->execute()) {
                        $message = '<div class="alert alert-success">User added successfully!</div>';
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() == 23000) {
                        $message = '<div class="alert alert-danger">Error: Email address is already registered.</div>';
                    } else {
                        error_log('Unable to add admin user: ' . $e->getMessage());
                        $message = '<div class="alert alert-danger">Unable to save the user account.</div>';
                    }
                }
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'edit_user') {
            $id = (int)$_POST['user_id'];
            $full_name = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            $role = in_array($_POST['role'] ?? '', ['superadmin', 'editor'], true) ? $_POST['role'] : 'editor';
            $status = isset($_POST['status']) && $_POST['status'] == 'active' ? 'active' : 'inactive';
            
            // Prevent locking oneself out
            if ($id == $current_user_id) {
                $status = 'active';
                $role = 'superadmin';
            }
            
            $password = $_POST['password'];
            
            try {
                if (!$email) {
                    throw new InvalidArgumentException('A valid email address is required.');
                }
                if (!empty($password) && strlen($password) < 12) {
                    throw new InvalidArgumentException('Password must be at least 12 characters.');
                }
                if (!empty($password)) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $db->query('UPDATE admin_users SET full_name=:name, email=:email, password_hash=:pass, role=:role, status=:status WHERE id=:id');
                    $db->bind(':pass', $password_hash);
                } else {
                    $db->query('UPDATE admin_users SET full_name=:name, email=:email, role=:role, status=:status WHERE id=:id');
                }
                
                $db->bind(':name', $full_name);
                $db->bind(':email', $email);
                $db->bind(':role', $role);
                $db->bind(':status', $status);
                $db->bind(':id', $id);
                
                if ($db->execute()) {
                    $message = '<div class="alert alert-success">User updated successfully!</div>';
                    // Update session if editing self
                    if ($id == $current_user_id) {
                        Auth::updateCurrentUser($full_name, $email, $role);
                    }
                }
            } catch (InvalidArgumentException $e) {
                $message = '<div class="alert alert-danger">' . htmlspecialchars($e->getMessage()) . '</div>';
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $message = '<div class="alert alert-danger">Error: Email address is already used by another account.</div>';
                } else {
                        error_log('Unable to update admin user: ' . $e->getMessage());
                        $message = '<div class="alert alert-danger">Unable to update the user account.</div>';
                }
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_user') {
            $id = (int)$_POST['user_id'];
            if ($id == $current_user_id) {
                $message = '<div class="alert alert-danger">You cannot delete your own account.</div>';
            } else {
                $db->query('DELETE FROM admin_users WHERE id=:id');
                $db->bind(':id', $id);
                $db->execute();
                $message = '<div class="alert alert-success">User account deleted.</div>';
            }
        }
    }
}

// Fetch users
$db->query('SELECT id, full_name, email, role, status, last_login, created_at FROM admin_users ORDER BY full_name ASC');
$users = $db->resultSet();
?>
<?php
$page_title = 'User Management';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>

<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="users" class="me-2 text-primary"></i> User Management</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i data-lucide="user-plus" class="me-2" style="width:18px;height:18px;"></i> Add User
        </button>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($users)): ?>
                    <tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>
                    <?php endif; ?>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td class="ps-4">
                            <strong><?php echo htmlspecialchars($u->full_name); ?></strong>
                            <?php if($u->id == $current_user_id): ?>
                                <span class="badge bg-primary ms-1" style="font-size:0.6rem;">You</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($u->email); ?></td>
                        <td>
                            <?php if($u->role == 'superadmin'): ?>
                                <span class="badge bg-dark">Super Admin</span>
                            <?php else: ?>
                                <span class="badge bg-secondary text-capitalize"><?php echo htmlspecialchars($u->role); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($u->status == 'active'): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted">
                                <?php echo $u->last_login ? date('M d, Y h:i A', strtotime($u->last_login)) : 'Never'; ?>
                            </small>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary me-1" onclick='editUser(<?php echo json_encode($u); ?>)'>
                                <i data-lucide="pencil" style="width:14px;height:14px;"></i>
                            </button>
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                <?php echo Csrf::getField(); ?>
                                <input type="hidden" name="action" value="delete_user">
                                <input type="hidden" name="user_id" value="<?php echo $u->id; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" <?php echo ($u->id == $current_user_id) ? 'disabled' : ''; ?>>
                                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Admin User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="save_user">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" name="full_name" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Email Address</label>
                  <input type="email" class="form-control" name="email" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" required minlength="6">
              </div>
              <div class="mb-3">
                  <label class="form-label">Role</label>
                  <select class="form-select" name="role">
                      <option value="admin">Admin</option>
                      <option value="superadmin">Super Admin</option>
                      <option value="editor">Editor</option>
                  </select>
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="status" value="active" id="status" checked>
                  <label class="form-check-label" for="status">Active Account</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Create User</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="" method="POST">
          <?php echo Csrf::getField(); ?>
          <input type="hidden" name="action" value="edit_user">
          <input type="hidden" name="user_id" id="edit_user_id">
          <div class="modal-body">
              <div class="mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" name="full_name" id="edit_full_name" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Email Address</label>
                  <input type="email" class="form-control" name="email" id="edit_email" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">New Password</label>
                  <input type="password" class="form-control" name="password" minlength="6">
                  <small class="text-muted d-block mt-1">Leave blank to keep current password.</small>
              </div>
              <div class="mb-3">
                  <label class="form-label">Role</label>
                  <select class="form-select" name="role" id="edit_role">
                      <option value="admin">Admin</option>
                      <option value="superadmin">Super Admin</option>
                      <option value="editor">Editor</option>
                  </select>
              </div>
              <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" name="status" value="active" id="edit_status">
                  <label class="form-check-label" for="edit_status">Active Account</label>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update User</button>
          </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
<script>
    function editUser(user) {
        document.getElementById('edit_user_id').value = user.id;
        document.getElementById('edit_full_name').value = user.full_name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_status').checked = (user.status === 'active');
        
        // Prevent disabling own account in UI (backend also protects this)
        var currentUserId = <?php echo json_encode($current_user_id); ?>;
        if (user.id == currentUserId) {
            document.getElementById('edit_status').disabled = true;
        } else {
            document.getElementById('edit_status').disabled = false;
        }

        var editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        editModal.show();
    }
</script>
