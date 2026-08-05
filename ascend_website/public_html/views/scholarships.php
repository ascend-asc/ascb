<?php
$page_title = 'Scholarships';
$page_description = 'Discover scholarship opportunities available at ASCB for deserving and qualified students.';
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<main id="main-content">

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Scholarships</h1>
        <p class="lead">Financial aid and scholarship opportunities at ASCB</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3"><span class="badge bg-warning text-dark px-3 py-2 fs-6">Government</span></div>
                    <h4 class="fw-bold text-primary">CHED Scholarship</h4>
                    <p class="text-muted">Commission on Higher Education scholarships for deserving students. Covers tuition fees and monthly allowance for qualified recipients.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3"><span class="badge bg-warning text-dark px-3 py-2 fs-6">Government</span></div>
                    <h4 class="fw-bold text-primary">TESDA Scholarship (STEP)</h4>
                    <p class="text-muted">Special Training for Employment Program providing free technical-vocational training for out-of-school youth and unemployed adults.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3"><span class="badge bg-primary text-white px-3 py-2 fs-6">Institutional</span></div>
                    <h4 class="fw-bold text-primary">Academic Excellence Award</h4>
                    <p class="text-muted">Awarded to students with outstanding academic performance. Full and partial tuition discount grants available for top performers.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3"><span class="badge bg-primary text-white px-3 py-2 fs-6">Institutional</span></div>
                    <h4 class="fw-bold text-primary">Athletic Scholarship</h4>
                    <p class="text-muted">For student-athletes who represent ASCB in regional and national competitions. Covers partial tuition fees and training support.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3"><span class="badge bg-success text-white px-3 py-2 fs-6">LGU</span></div>
                    <h4 class="fw-bold text-primary">LGU-Bislig Scholarship</h4>
                    <p class="text-muted">Scholarship grants from the Local Government Unit of Bislig City for qualified residents. Subject to LGU requirements and availability.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="mb-3"><span class="badge bg-secondary text-white px-3 py-2 fs-6">Private</span></div>
                    <h4 class="fw-bold text-primary">Corporate Grants</h4>
                    <p class="text-muted">Scholarships offered by partner corporations and industries. Includes immersion opportunities and employment prospects upon graduation.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 p-5 rounded-4" style="background: linear-gradient(135deg, #0B2F6B, #1F4E9C);">
        <h3 class="text-white fw-bold mb-3">Apply for a Scholarship</h3>
        <p class="text-white-50 mb-4">Visit the Registrar's Office or contact the Student Affairs Office to learn about currently available scholarships and grant requirements.</p>
        <a href="<?php echo URLROOT; ?>/contact" class="btn btn-warning fw-bold px-5 py-3">Contact Us to Inquire</a>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
