<?php require_once __DIR__ . '/partials/header.php'; ?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Student Life</h1>
        <p class="lead">Enriching experiences beyond the classroom at ASCB</p>
    </div>
</div>

<div class="container py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6 mb-4">
            <h2 class="fw-bold text-primary mb-3">Life at ASCB</h2>
            <p class="text-muted">At Andres Soriano Colleges of Bislig, student life goes beyond academics. We foster a vibrant community where students develop leadership, creativity, and camaraderie.</p>
            <p class="text-muted">From cultural festivals to sports competitions, community outreach programs to academic organizations, every student has an opportunity to grow holistically.</p>
        </div>
        <div class="col-md-6 mb-4">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="display-4 mb-2">🎭</div>
                        <h6 class="fw-bold">Cultural Events</h6>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="display-4 mb-2">⚽</div>
                        <h6 class="fw-bold">Sports & Athletics</h6>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="display-4 mb-2">🤝</div>
                        <h6 class="fw-bold">Community Service</h6>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="display-4 mb-2">📚</div>
                        <h6 class="fw-bold">Academic Clubs</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="fw-bold text-primary mb-4">Student Organizations</h3>
    <div class="row g-3 mb-5">
        <?php
        $orgs = [
            ['name' => 'Supreme Student Government (SSG)', 'desc' => 'The highest governing body of ASCB student organizations.', 'icon' => '🏛️'],
            ['name' => 'College Student Councils', 'desc' => 'Department-based student councils for each academic program.', 'icon' => '🎓'],
            ['name' => 'ASCB Chorale', 'desc' => 'Official chorale representing ASCB in local and regional competitions.', 'icon' => '🎵'],
            ['name' => 'Dance Troupe', 'desc' => 'Representing ASCB in folk dance and modern dance competitions.', 'icon' => '💃'],
            ['name' => 'Campus Journalism Club', 'desc' => 'Student writers, editors, and journalists of the school publication.', 'icon' => '📰'],
            ['name' => 'Science & Technology Club', 'desc' => 'Promoting STEM education and innovation among ASCB students.', 'icon' => '🔬'],
        ];
        foreach($orgs as $org): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 p-3 d-flex flex-row align-items-center gap-3">
                <div style="font-size: 2.5rem;"><?php echo $org['icon']; ?></div>
                <div>
                    <h6 class="fw-bold mb-1"><?php echo $org['name']; ?></h6>
                    <p class="small text-muted mb-0"><?php echo $org['desc']; ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
