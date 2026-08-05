<?php
$page_title = 'Admissions';
$page_description = 'Start your journey at ASCB. Learn about admissions requirements, enrollment procedures, and how to apply.';
?>
<?php
require_once __DIR__ . '/partials/header.php';
?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Admissions & Enrollment</h1>
        <p class="lead">Join the ASCB Community</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4">
                <h4 class="text-primary fw-bold mb-3"><i data-lucide="user-plus"></i> Freshmen</h4>
                <ul class="list-unstyled text-muted lh-lg">
                    <li><i data-lucide="check-circle"></i> Form 138 (Report Card)</li>
                    <li><i data-lucide="check-circle"></i> Certificate of Good Moral</li>
                    <li><i data-lucide="check-circle"></i> PSA Birth Certificate</li>
                    <li><i data-lucide="check-circle"></i> 2x2 ID Photos</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4">
                <h4 class="text-primary fw-bold mb-3"><i data-lucide="refresh-cw"></i> Transferees</h4>
                <ul class="list-unstyled text-muted lh-lg">
                    <li><i data-lucide="check-circle"></i> Honorable Dismissal</li>
                    <li><i data-lucide="check-circle"></i> Transcript of Records (TOR)</li>
                    <li><i data-lucide="check-circle"></i> Certificate of Good Moral</li>
                    <li><i data-lucide="check-circle"></i> PSA Birth Certificate</li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 p-4 bg-light">
                <h4 class="text-primary fw-bold mb-3"><i data-lucide="award"></i> Scholarships</h4>
                <p class="text-muted">ASCB accepts various national & local government scholarships:</p>
                <ul class="list-unstyled text-muted small">
                    <li>• CHED UniFAST / TES / TDP</li>
                    <li>• LGU & Provincial Assistance</li>
                    <li>• Alay ng Probinsya Grants</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>


