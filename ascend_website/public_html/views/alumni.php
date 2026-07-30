<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Alumni</h1>
        <p class="lead">Connecting graduates, celebrating achievements, building legacies</p>
    </div>
</div>

<div class="container py-5">
    <!-- Welcome Banner -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 mb-4">
            <h2 class="fw-bold text-primary mb-3">Welcome, ASCB Alumni!</h2>
            <p class="text-muted">Once an Ascending Eagle, always an Ascending Eagle. Our alumni are our greatest pride — living proof of ASCB's commitment to excellence and community service.</p>
            <p class="text-muted">The ASCB Alumni Association serves as the bridge between our graduates and the institution, fostering lifelong relationships and mutual support.</p>
            <a href="<?php echo URLROOT; ?>/contact" class="btn btn-primary mt-2 px-4 py-2 fw-bold">Get in Touch</a>
        </div>
        <div class="col-md-6 mb-4">
            <div class="row g-3 text-center">
                <div class="col-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h2 class="fw-bold text-warning mb-0">70+</h2>
                        <p class="text-muted mb-0 small">Years of Excellence</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h2 class="fw-bold text-warning mb-0">5,000+</h2>
                        <p class="text-muted mb-0 small">Graduates</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h2 class="fw-bold text-warning mb-0">Local</h2>
                        <p class="text-muted mb-0 small">& International</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm p-4">
                        <h2 class="fw-bold text-warning mb-0">Various</h2>
                        <p class="text-muted mb-0 small">Industries</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alumni Benefits -->
    <h3 class="fw-bold text-primary mb-4">Alumni Benefits</h3>
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4">
                <div style="font-size:3rem;">🤝</div>
                <h5 class="fw-bold mt-3">Networking Events</h5>
                <p class="text-muted small">Connect with fellow graduates, faculty, and industry professionals through regular reunions and networking events.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4">
                <div style="font-size:3rem;">📚</div>
                <h5 class="fw-bold mt-3">Library Access</h5>
                <p class="text-muted small">Alumni retain access to ASCB's library resources and facilities for continuing education and professional development.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4">
                <div style="font-size:3rem;">💼</div>
                <h5 class="fw-bold mt-3">Job Referrals</h5>
                <p class="text-muted small">The Alumni Association facilitates job referrals and career opportunities through our growing network of partner employers.</p>
            </div>
        </div>
    </div>

    <!-- Register CTA -->
    <div class="text-center p-5 rounded-4" style="background: linear-gradient(135deg, #0B2F6B, #1F4E9C);">
        <h3 class="text-white fw-bold mb-3">Register as an ASCB Alumnus</h3>
        <p class="text-white-50 mb-4">Help us build a stronger alumni community. Register your information with the Alumni Affairs Office to stay connected and receive updates.</p>
        <a href="<?php echo URLROOT; ?>/contact" class="btn btn-warning fw-bold px-5 py-3">Register Now</a>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
