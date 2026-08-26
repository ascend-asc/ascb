<?php
$page_title = 'Academics';
$page_description = 'Explore the academic programs offered at ASCB including higher education, technical-vocational, and basic education.';
?>
<?php
$db->query("SELECT * FROM programs WHERE is_active = 1 ORDER BY FIELD(department,'Basic Ed','Diploma','College'), name ASC");
$programs = $db->resultSet();

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Academic Programs</h1>
        <p class="lead">College, Diploma, and Basic Education Offerings</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php if(empty($programs)): ?>
            <p class="text-center text-muted">No active programs listed.</p>
        <?php else: ?>
            <?php foreach($programs as $prog): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-warning text-dark align-self-start mb-2"><?php echo htmlspecialchars($prog->department); ?></span>
                        <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($prog->name); ?></h5>
                        <p class="card-text text-muted flex-grow-1"><?php echo nl2br(htmlspecialchars($prog->description ?? '')); ?></p>
                        <?php if($prog->brochure_pdf): ?>
                            <a href="/<?php echo htmlspecialchars($prog->brochure_pdf); ?>" target="_blank" class="btn btn-outline-primary mt-3"><i data-lucide="file-text"></i> Download Brochure</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>


