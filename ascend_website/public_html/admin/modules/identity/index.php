<?php
require_once '../../../../config/config.php';
require_once '../../../../app/core/Database.php';
require_once '../../../../app/core/Auth.php';
require_once '../../../../app/core/Csrf.php';

Auth::requireLogin();
$db = Database::getInstance();

$message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Invalid CSRF token.</div>';
    } else {
        $vision = $_POST['vision'];
        $mission = $_POST['mission'];
        $motto = filter_var($_POST['motto'], FILTER_SANITIZE_STRING);
        
        $core_values = [
            'A' => ['label' => 'Accountability', 'desc' => filter_var($_POST['cv_a'], FILTER_SANITIZE_STRING)],
            'S' => ['label' => 'Stewardship', 'desc' => filter_var($_POST['cv_s'], FILTER_SANITIZE_STRING)],
            'C' => ['label' => 'Compassion', 'desc' => filter_var($_POST['cv_c'], FILTER_SANITIZE_STRING)],
            'B' => ['label' => 'Brilliance', 'desc' => filter_var($_POST['cv_b'], FILTER_SANITIZE_STRING)]
        ];
        
        $db->query('SELECT id FROM site_identity LIMIT 1');
        $existing = $db->single();
        
        if ($existing) {
            $db->query('UPDATE site_identity SET vision = :v, mission = :m, motto = :mt, core_values = :cv WHERE id = :id');
            $db->bind(':id', $existing->id);
        } else {
            $db->query('INSERT INTO site_identity (vision, mission, motto, core_values) VALUES (:v, :m, :mt, :cv)');
        }
        
        $db->bind(':v', $vision);
        $db->bind(':m', $mission);
        $db->bind(':mt', $motto);
        $db->bind(':cv', json_encode($core_values));
        
        if ($db->execute()) {
            $message = '<div class="alert alert-success">Institutional Identity updated successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to update.</div>';
        }
    }
}

// Fetch Current Data
$db->query('SELECT * FROM site_identity LIMIT 1');
$identity = $db->single();

$cv = ['A'=>['desc'=>''], 'S'=>['desc'=>''], 'C'=>['desc'=>''], 'B'=>['desc'=>'']];
if ($identity && $identity->core_values) {
    $parsed_cv = json_decode($identity->core_values, true);
    if (is_array($parsed_cv)) {
        $cv = array_merge($cv, $parsed_cv);
    }
}
?>
<?php
$page_title = 'Institutional Identity';
require_once __DIR__ . '/../../partials/header.php';
require_once __DIR__ . '/../../partials/sidebar.php';
?>
<main class="main-content" id="mainContent">
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4><i data-lucide="building-2"></i> Institutional Identity</h4>
        </div>

    <?php echo $message; ?>

    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form action="" method="POST">
                <?php echo Csrf::getField(); ?>
                
                <h5 class="text-primary mb-3">Motto</h5>
                <div class="mb-4">
                    <input type="text" class="form-control form-control-lg" name="motto" value="<?php echo htmlspecialchars($identity->motto ?? 'ASCB, Ascending!'); ?>">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary mb-3">Vision</h5>
                        <div class="mb-4">
                            <textarea class="form-control" name="vision" rows="6" required><?php echo htmlspecialchars($identity->vision ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary mb-3">Mission</h5>
                        <div class="mb-4">
                            <textarea class="form-control" name="mission" rows="6" required><?php echo htmlspecialchars($identity->mission ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-primary mb-3">Core Values (A-S-C-B)</h5>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="fw-bold">A - Accountability</label>
                        <textarea class="form-control mt-2" name="cv_a" rows="3"><?php echo htmlspecialchars($cv['A']['desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold">S - Stewardship</label>
                        <textarea class="form-control mt-2" name="cv_s" rows="3"><?php echo htmlspecialchars($cv['S']['desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold">C - Compassion</label>
                        <textarea class="form-control mt-2" name="cv_c" rows="3"><?php echo htmlspecialchars($cv['C']['desc'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-bold">B - Brilliance</label>
                        <textarea class="form-control mt-2" name="cv_b" rows="3"><?php echo htmlspecialchars($cv['B']['desc'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="mt-5 text-end">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>lucide.createIcons();</script></body>
</html>



