<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';

Auth::requireLogin();

$message = '';
$uploads_dir = '../../../../public_html/uploads';
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token']) && Csrf::validateToken($_POST['csrf_token'])) {
    
    // Upload File
    if (isset($_POST['action']) && $_POST['action'] == 'upload') {
        $category = $_POST['category'] ?? 'misc';
        $target_dir = $uploads_dir . '/' . preg_replace('/[^a-zA-Z0-9_-]/', '', $category);
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed_extensions)) {
                $new_name = uniqid('media_') . '.' . $ext;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . '/' . $new_name)) {
                    $message = '<div class="alert alert-success"><i data-lucide="check-circle" class="me-2"></i>File uploaded successfully.</div>';
                } else {
                    $message = '<div class="alert alert-danger">Failed to move uploaded file.</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">File type not allowed.</div>';
            }
        } else {
             $message = '<div class="alert alert-danger">Error uploading file.</div>';
        }
    }

    // Delete File
    if (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $file_path = $_POST['file_path'] ?? '';
        // Security check to prevent directory traversal
        if (strpos($file_path, 'uploads/') === 0 && strpos($file_path, '..') === false) {
            $full_path = '../../../../public_html/' . $file_path;
            if (file_exists($full_path) && is_file($full_path)) {
                unlink($full_path);
                $message = '<div class="alert alert-success"><i data-lucide="check-circle" class="me-2"></i>File deleted.</div>';
            }
        }
    }
}

// Fetch files
$files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploads_dir));
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $ext = strtolower($file->getExtension());
        if (in_array($ext, $allowed_extensions)) {
            $rel_path = str_replace(realpath($uploads_dir), '', $file->getRealPath());
            $rel_path = ltrim(str_replace('\\', '/', $rel_path), '/');
            $files[] = [
                'name' => $file->getFilename(),
                'path' => 'uploads/' . $rel_path,
                'size' => round($file->getSize() / 1024, 2) . ' KB',
                'date' => date('Y-m-d H:i', $file->getMTime()),
                'type' => in_array($ext, ['jpg','jpeg','png','gif','webp']) ? 'image' : 'document'
            ];
        }
    }
}

// Sort newest first
usort($files, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

$page_title = 'Media Library';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<style>
    .media-card { transition: transform 0.2s; cursor: pointer; height: 100%; }
    .media-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .media-preview { height: 160px; object-fit: cover; width: 100%; background: #f8f9fa; display: flex; align-items: center; justify-content: center; }
    .media-preview i { width: 60px; height: 60px; color: #adb5bd; }
</style>

<main class="main-content" id="mainContent">
    <div class="container-fluid py-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i data-lucide="folder" class="me-2 text-primary"></i> Media Library</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i data-lucide="upload" class="me-2" style="width:18px;height:18px;"></i> Upload File
            </button>
        </div>

        <?php echo $message; ?>

        <div class="row g-4">
            <?php if(empty($files)): ?>
                <div class="col-12"><div class="alert alert-info">No media files found.</div></div>
            <?php else: ?>
                <?php foreach($files as $f): ?>
                <div class="col-md-4 col-lg-3 col-xl-2">
                    <div class="card shadow-sm media-card position-relative">
                        <?php if($f['type'] == 'image'): ?>
                            <img src="<?php echo URLROOT . '/' . $f['path']; ?>" class="card-img-top media-preview" alt="Media">
                        <?php else: ?>
                            <div class="card-img-top media-preview">
                                <i data-lucide="file-text"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body p-3">
                            <h6 class="text-truncate mb-1" title="<?php echo htmlspecialchars($f['name']); ?>" style="font-size:0.9rem;">
                                <?php echo htmlspecialchars($f['name']); ?>
                            </h6>
                            <div class="text-muted" style="font-size:0.75rem;">
                                <?php echo $f['size']; ?> &bull; <?php echo $f['date']; ?>
                            </div>
                        </div>

                        <!-- Actions Overlay (visible on hover or focus) -->
                        <div class="position-absolute top-0 end-0 p-2">
                            <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this file? This might break pages using it.');">
                                <?php echo Csrf::getField(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="file_path" value="<?php echo htmlspecialchars($f['path']); ?>">
                                <button type="submit" class="btn btn-sm btn-danger rounded-circle shadow" title="Delete" style="width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center;">
                                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <?php echo Csrf::getField(); ?>
                <input type="hidden" name="action" value="upload">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Media</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category / Folder</label>
                        <select name="category" class="form-select">
                            <option value="misc">Miscellaneous (misc)</option>
                            <option value="slides">Hero Slides (slides)</option>
                            <option value="news">News & Events (news)</option>
                            <option value="programs">Programs (programs)</option>
                            <option value="staff">Staff Photos (staff)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select File</label>
                        <input type="file" name="file" class="form-control" required accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx">
                        <small class="text-muted d-block mt-1">Allowed: JPG, PNG, GIF, WEBP, PDF, DOC. Max size depends on server config.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../partials/footer.php'; ?>
