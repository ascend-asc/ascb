<?php
$page_title = 'Academics';
$page_description = 'Explore the academic programs offered at ASCB including higher education, technical-vocational, and basic education.';
?>
<?php
$db->query("SELECT * FROM programs WHERE is_active = 1");
$all_programs = $db->resultSet();

// Define the desired order within each category
$basic_ed_order  = ['Elementary Department', 'Junior High School', 'Senior High School'];
$college_order   = [
    'College of Accountancy Education (CAE)',
    'College of Business Administration and Education (CBAE)',
    'College of Computer Education (CCE)',
    'College of Criminal Justice Education (CCJE)',
    'College of Teacher Education (CTE)',
];
$diploma_order   = [
    'Diploma Business of Operation Technology (DBOT)',
    'Diploma Information System Technology (DIST)',
    'Diploma Information Technology (DIT)',
    'Diploma of Security Operation Technology (DSOT)',
];

// Group programs by category
$groups = ['Basic Ed' => [], 'College' => [], 'Diploma' => []];
foreach ($all_programs as $prog) {
    $dept = $prog->department;
    if ($dept === 'TVET') $dept = 'Diploma';
    if (isset($groups[$dept])) {
        $groups[$dept][] = $prog;
    }
}

// Sort each group in the defined order
function sortByOrder($arr, $order) {
    usort($arr, function($a, $b) use ($order) {
        $ai = array_search($a->name, $order);
        $bi = array_search($b->name, $order);
        if ($ai === false) $ai = PHP_INT_MAX;
        if ($bi === false) $bi = PHP_INT_MAX;
        return $ai - $bi;
    });
    return $arr;
}
$groups['Basic Ed'] = sortByOrder($groups['Basic Ed'], $basic_ed_order);
$groups['College']  = sortByOrder($groups['College'],  $college_order);
$groups['Diploma']  = sortByOrder($groups['Diploma'],  $diploma_order);

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Academic Programs</h1>
        <p class="lead">College, Diploma, and Basic Education Offerings</p>
    </div>
</div>

<div class="container py-5">
    <?php if(empty($all_programs)): ?>
        <p class="text-center text-muted">No active programs listed.</p>
    <?php else: ?>
    <div class="row g-4 align-items-start">
        <?php foreach (['Basic Ed', 'College', 'Diploma'] as $category): ?>
        <div class="col-md-4 d-flex flex-column gap-4">
            <?php foreach ($groups[$category] as $prog): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-warning text-dark align-self-start mb-2"><?php echo htmlspecialchars($category); ?></span>
                    <h5 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($prog->name); ?></h5>
                    <p class="card-text text-muted flex-grow-1"><?php echo nl2br(htmlspecialchars($prog->description ?? '')); ?></p>
                    <?php if($prog->brochure_pdf): ?>
                        <a href="/<?php echo htmlspecialchars($prog->brochure_pdf); ?>" target="_blank" class="btn btn-outline-primary mt-3"><i data-lucide="file-text"></i> Download Brochure</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>


