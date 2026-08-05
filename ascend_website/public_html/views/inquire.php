<?php
$page_title = 'Inquire Now';
$page_description = 'Have questions? Submit your inquiry to ASCB and our team will get back to you shortly.';
?>
<?php require_once __DIR__ . '/partials/header.php'; ?>
<main id="main-content">

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Inquire Now</h1>
        <p class="lead">We're happy to answer any questions you have</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg p-4 p-md-5 rounded-4">
                <h3 class="fw-bold text-primary mb-4 text-center">Send Us Your Inquiry</h3>
                <form action="<?php echo URLROOT; ?>/contact" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" class="form-control form-control-lg" name="name" placeholder="Your full name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control form-control-lg" name="email" placeholder="your@email.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contact Number</label>
                            <input type="tel" class="form-control form-control-lg" name="phone" placeholder="+63 9XX XXX XXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Inquiry Type</label>
                            <select class="form-select form-select-lg" name="inquiry_type" required>
                                <option value="">Select topic...</option>
                                <option>Admissions & Enrollment</option>
                                <option>Scholarships & Financial Aid</option>
                                <option>Programs & Courses</option>
                                <option>Student Life</option>
                                <option>Alumni Affairs</option>
                                <option>General Inquiry</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Your Message</label>
                            <textarea class="form-control form-control-lg" name="message" rows="5" placeholder="Type your question or message here..." required></textarea>
                        </div>
                        <div class="col-12 text-center mt-2">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold">Submit Inquiry</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>
