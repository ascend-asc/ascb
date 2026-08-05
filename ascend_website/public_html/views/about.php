<?php
$page_title = 'About Us';
$page_description = 'Learn about the history, vision, mission, and core values of Andres Soriano Colleges of Bislig (ASCB).';
?>
<?php
// Fetch Identity & Staff
$db->query('SELECT * FROM site_identity LIMIT 1');
$identity = $db->single();

$db->query('SELECT * FROM staff_directory ORDER BY id ASC');
$staff = $db->resultSet();

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">About ASCB</h1>
        <p class="lead">History, Vision, Mission, and Leadership</p>
    </div>
</div>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <h2 class="text-primary fw-bold mb-3">Our History</h2>
            <p>Andres Soriano Colleges of Bislig (ASCB) was established in 1952 with the commitment to provide quality education in Bislig City and surrounding regions in Surigao del Sur.</p>
            <p>Over decades of steadfast dedication, ASCB has grown into a premier educational institution providing Basic Education, TVET/TESDA courses, and Higher Education degree programs.</p>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 bg-light p-4">
                <h3 class="text-primary fw-bold mb-3">Motto & Brand</h3>
                <h4 class="text-warning fw-bold mb-3"><?php echo htmlspecialchars($identity->motto ?? 'ASCB, Ascending!'); ?></h4>
                <p><strong>Colors:</strong> ASCB Blue (Nobility, sincerity, loyalty) & White (Purity of heart and mind).</p>
            </div>
        </div>
    </div>

    <!-- Organizational Structure / Staff -->
    <div class="my-5">
        <h2 class="text-center text-primary fw-bold mb-4">Administration & Faculty</h2>
        <div class="row g-4">
            <?php if(empty($staff)): ?>
                <p class="text-center text-muted">Faculty directory will be updated soon.</p>
            <?php else: ?>
                <?php foreach($staff as $person): ?>
                <div class="col-md-3 text-center">
                    <div class="card h-100 shadow-sm border-0 p-3">
                        <?php if($person->photo): ?>
                            <img src="<?php echo URLROOT; ?>/<?php echo htmlspecialchars($person->photo); ?>" class="rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2rem;">
                                <i data-lucide="user"></i>
                            </div>
                        <?php endif; ?>
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($person->name); ?></h5>
                        <p class="text-muted small mb-1"><?php echo htmlspecialchars($person->position); ?></p>
                        <span class="badge bg-light text-dark border align-self-center mt-auto"><?php echo htmlspecialchars($person->department); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>


