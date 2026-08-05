<?php
$page_title = 'Policies';
$page_description = 'Read the academic and institutional policies of Andres Soriano Colleges of Bislig.';
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<main id="main-content">

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Policies</h1>
        <p class="lead">Institutional rules, guidelines, and student handbook</p>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="list-group sticky-top" style="top: 100px;">
                <a href="#academic" class="list-group-item list-group-item-action fw-semibold">📋 Academic Policies</a>
                <a href="#conduct" class="list-group-item list-group-item-action fw-semibold">⚖️ Code of Conduct</a>
                <a href="#attendance" class="list-group-item list-group-item-action fw-semibold">📅 Attendance</a>
                <a href="#grading" class="list-group-item list-group-item-action fw-semibold">🎯 Grading System</a>
                <a href="#dress" class="list-group-item list-group-item-action fw-semibold">👔 Dress Code</a>
            </div>
        </div>
        <div class="col-lg-9">
            <div id="academic" class="mb-5">
                <h3 class="fw-bold text-primary border-bottom pb-2 mb-4">Academic Policies</h3>
                <p>ASCB is committed to maintaining high academic standards. All students are expected to pursue their studies with diligence, integrity, and a genuine desire to learn.</p>
                <ul class="text-muted">
                    <li class="mb-2">Students must maintain a satisfactory academic standing each semester to continue enrollment.</li>
                    <li class="mb-2">Academic dishonesty, including cheating and plagiarism, shall result in disciplinary action.</li>
                    <li class="mb-2">Students on academic probation must consult with their program adviser.</li>
                    <li class="mb-2">All incomplete grades must be resolved within the prescribed period.</li>
                </ul>
            </div>

            <div id="conduct" class="mb-5">
                <h3 class="fw-bold text-primary border-bottom pb-2 mb-4">Code of Conduct</h3>
                <p>Every ASCB student is a representative of the institution. Students are expected to uphold the values of Accountability, Stewardship, Compassion, and Brilliance (ASCB) in all their actions.</p>
                <ul class="text-muted">
                    <li class="mb-2">Respect for all members of the academic community is mandatory.</li>
                    <li class="mb-2">Vandalism, theft, or destruction of school property is strictly prohibited.</li>
                    <li class="mb-2">Use of illegal substances on campus shall result in immediate dismissal.</li>
                    <li class="mb-2">Bullying or harassment in any form is a serious offense.</li>
                </ul>
            </div>

            <div id="attendance" class="mb-5">
                <h3 class="fw-bold text-primary border-bottom pb-2 mb-4">Attendance Policy</h3>
                <p>Regular attendance is crucial for academic success. Students who miss more than 20% of the total class hours in a subject shall receive a grade of "Dropped" (DR).</p>
                <div class="alert alert-warning">
                    <strong>Important:</strong> Three (3) consecutive absences without valid reason will be reported to the Office of Student Affairs.
                </div>
            </div>

            <div id="grading" class="mb-5">
                <h3 class="fw-bold text-primary border-bottom pb-2 mb-4">Grading System</h3>
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr><th>Grade</th><th>Percentage Equivalent</th><th>Description</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>1.0</td><td>96–100%</td><td>Excellent</td></tr>
                        <tr><td>1.25</td><td>93–95%</td><td>Superior</td></tr>
                        <tr><td>1.5</td><td>90–92%</td><td>Very Good</td></tr>
                        <tr><td>1.75</td><td>87–89%</td><td>Good</td></tr>
                        <tr><td>2.0</td><td>84–86%</td><td>Above Average</td></tr>
                        <tr><td>2.25</td><td>81–83%</td><td>Average</td></tr>
                        <tr><td>2.5</td><td>78–80%</td><td>Satisfactory</td></tr>
                        <tr><td>2.75</td><td>75–77%</td><td>Passing</td></tr>
                        <tr><td>5.0</td><td>Below 75%</td><td>Failed</td></tr>
                    </tbody>
                </table>
            </div>

            <div id="dress" class="mb-5">
                <h3 class="fw-bold text-primary border-bottom pb-2 mb-4">Dress Code</h3>
                <p>Students must wear their prescribed school uniform during class days. The prescribed uniform reflects the dignity and identity of ASCB.</p>
                <ul class="text-muted">
                    <li class="mb-2">College students must wear the prescribed uniform of their department.</li>
                    <li class="mb-2">ID must be worn and visible at all times within school premises.</li>
                    <li class="mb-2">Casual Friday attire is allowed subject to department guidelines.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
