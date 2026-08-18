<?php
$page_title = 'Contact Us';
$page_description = 'Get in touch with Andres Soriano Colleges of Bislig. Find our address, phone number, and email.';
?>
<?php
require_once __DIR__ . '/../../app/core/Csrf.php';
require_once __DIR__ . '/../../app/core/Security.php';
require_once __DIR__ . '/../../app/core/RateLimiter.php';

$message = '';
$csrfToken = Csrf::generateToken();
$rateLimiter = new RateLimiter('ascb-inquiries');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allowedFormTypes = [
        'Contact Inquiry', 'General Inquiry', 'Admissions Inquiry', 'Registrar Request',
        'Admissions & Enrollment', 'Scholarships & Financial Aid', 'Programs & Courses',
        'Student Life', 'Alumni Affairs',
    ];
    $name = Security::text($_POST['name'] ?? '', 100);
    $email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
    $phone = Security::text($_POST['phone'] ?? '', 20);
    $msg = Security::text($_POST['message'] ?? '', 5000);
    $form_type = Security::text($_POST['form_type'] ?? $_POST['inquiry_type'] ?? 'Contact Inquiry', 50);
    $honeypot = trim((string) ($_POST['website'] ?? ''));
    $lastInquiry = (int) ($_SESSION['last_inquiry_at'] ?? 0);
    $rateKey = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $message = '<div class="alert alert-danger">Your form session expired. Please try again.</div>';
    } elseif ($honeypot !== '') {
        $message = '<div class="alert alert-success">Thank you! Your inquiry has been received.</div>';
    } elseif (time() - $lastInquiry < 30) {
        $message = '<div class="alert alert-warning">Please wait before submitting another inquiry.</div>';
    } elseif ($rateLimiter->tooManyAttempts($rateKey, 5, 3600)) {
        $message = '<div class="alert alert-warning">Too many inquiries have been submitted. Please try again later.</div>';
    } elseif (!$email || empty($name) || empty($msg)) {
        $message = '<div class="alert alert-warning">Please provide a valid name, email address, and message.</div>';
    } elseif (!in_array($form_type, $allowedFormTypes, true)) {
        $message = '<div class="alert alert-warning">Please select a valid inquiry type.</div>';
    } else {
        $db->query('INSERT INTO inquiries (name, email, phone, message, form_type) VALUES (:n, :e, :p, :m, :ft)');
        $db->bind(':n', $name);
        $db->bind(':e', $email);
        $db->bind(':p', $phone);
        $db->bind(':m', $msg);
        $db->bind(':ft', $form_type);

        if ($db->execute()) {
            $rateLimiter->hit($rateKey, 3600);
            $_SESSION['last_inquiry_at'] = time();
            $message = '<div class="alert alert-success">Thank you! Your inquiry has been sent successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to send inquiry. Please try again.</div>';
        }
    }
}

require_once __DIR__ . '/partials/header.php';
?>

<div class="page-hero text-white">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold">Contact Us</h1>
        <p class="lead">We'd love to hear from you</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-md-6">
            <h3 class="text-primary fw-bold mb-4">Send an Inquiry</h3>
            <?php echo $message; ?>
            <form action="" method="POST">
                <?php echo Csrf::getField(); ?>
                <div class="d-none" aria-hidden="true">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="mb-3">
                    <label class="form-label">Inquiry Type</label>
                    <select class="form-select" name="form_type">
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Admissions Inquiry">Admissions Inquiry</option>
                        <option value="Registrar Request">Registrar Request</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Message *</label>
                    <textarea class="form-control" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark w-100">Submit Inquiry</button>
            </form>
        </div>

        <div class="col-md-6">
            <h3 class="text-primary fw-bold mb-4">Campus Information</h3>
            <div class="card border-0 shadow-sm p-4 mb-4">
                <p><i data-lucide="map-pin"></i> <strong>Address:</strong> Andres Soriano Ave, Mangagoy, Bislig City, Surigao del Sur</p>
                <p><i data-lucide="phone"></i> <strong>Phone:</strong> (086) 853-2222</p>
                <p><i data-lucide="mail"></i> <strong>Email:</strong> info@ascb.edu.ph</p>
            </div>
            <!-- Google Maps Embed Placeholder -->
            <div class="ratio ratio-16x9 rounded shadow-sm overflow-hidden">
                <iframe src="https://maps.google.com/maps?q=Bislig+City&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/partials/footer.php'; ?>


