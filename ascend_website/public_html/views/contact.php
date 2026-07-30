<?php
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $form_type = filter_var($_POST['form_type'] ?? 'Contact Inquiry', FILTER_SANITIZE_STRING);

    if (!empty($name) && !empty($email) && !empty($msg)) {
        $db->query('INSERT INTO inquiries (name, email, phone, message, form_type) VALUES (:n, :e, :p, :m, :ft)');
        $db->bind(':n', $name);
        $db->bind(':e', $email);
        $db->bind(':p', $phone);
        $db->bind(':m', $msg);
        $db->bind(':ft', $form_type);

        if ($db->execute()) {
            $message = '<div class="alert alert-success">Thank you! Your inquiry has been sent successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to send inquiry. Please try again.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">Please fill in all required fields.</div>';
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


