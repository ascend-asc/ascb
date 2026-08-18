<?php
require_once '../../config/config.php';
require_once '../../app/core/Database.php';
require_once '../../app/core/Auth.php';
require_once '../../app/core/Csrf.php';
require_once '../../app/core/RateLimiter.php';

header('Cache-Control: no-store, private');

// If already logged in, redirect to dashboard
if (Auth::isLoggedIn()) {
    header('Location: ' . URLROOT . '/admin/index.php');
    exit;
}

$error = '';
$rateLimiter = new RateLimiter('ascb-login');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$lockedUntil = (int) ($_SESSION['login_locked_until'] ?? 0);
$isLocked = $lockedUntil > time();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF
    if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token.';
    } elseif ($isLocked) {
        $error = 'Too many login attempts. Please wait a few minutes and try again.';
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = (string) ($_POST['password'] ?? '');
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $rateKey = $clientIp . '|' . strtolower((string) $email);

        if (empty($email) || empty($password)) {
            $error = 'Please enter email and password.';
        } elseif ($rateLimiter->tooManyAttempts($rateKey, 5, 900)) {
            $error = 'Too many login attempts. Please wait a few minutes and try again.';
        } else {
            try {
                $db = Database::getInstance();
                $db->query('SELECT * FROM admin_users WHERE email = :email AND status = "active"');
                $db->bind(':email', $email);
                $row = $db->single();

                if ($row && password_verify($password, $row->password_hash)) {
                    $rateLimiter->clear($rateKey);
                    // Update last login
                    $db->query('UPDATE admin_users SET last_login = NOW() WHERE id = :id');
                    $db->bind(':id', $row->id);
                    $db->execute();

                    Auth::login($row);
                    header('Location: ' . URLROOT . '/admin/index.php');
                    exit;
                } else {
                    $rateLimiter->hit($rateKey, 900);
                    $_SESSION['login_failures'] = (int) ($_SESSION['login_failures'] ?? 0) + 1;
                    if ($_SESSION['login_failures'] >= 5) {
                        $_SESSION['login_locked_until'] = time() + 900;
                    }
                    usleep(300000);
                    $error = 'Invalid email or password.';
                }
            } catch (Throwable $e) {
                error_log('Admin authentication service unavailable: ' . $e->getMessage());
                $error = 'Login is temporarily unavailable. Please try again later.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - ASCB</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .brand-logo {
            max-width: 120px;
            margin-bottom: 20px;
        }
        .bg-ascb-blue {
            background-color: #0B2F6B;
            color: white;
        }
        .btn-ascb {
            background-color: #1F4E9C;
            color: white;
        }
        .btn-ascb:hover {
            background-color: #0B2F6B;
            color: white;
        }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="card-header bg-ascb-blue text-center py-4">
        <img src="<?php echo URLROOT; ?>/assets/images/ascb-logo-transparent.png" alt="ASCB Logo" class="brand-logo">
        <h4 class="mb-0">CMS Admin Login</h4>
    </div>
    <div class="card-body p-4">
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <?php echo Csrf::getField(); ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-ascb">Login</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
