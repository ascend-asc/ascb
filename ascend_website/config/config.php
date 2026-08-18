<?php

$localConfig = __DIR__ . '/config.local.php';

if (file_exists($localConfig)) {
    require_once $localConfig;
}

defined('DB_HOST') || define('DB_HOST', '127.0.0.1');
defined('DB_USER') || define('DB_USER', 'root');
defined('DB_PASS') || define('DB_PASS', '');
defined('DB_NAME') || define('DB_NAME', 'ascend_db');

defined('APPROOT') || define('APPROOT', dirname(__DIR__));
defined('URLROOT') || define('URLROOT', 'http://localhost/ascend_website/public_html');
defined('APP_ENV') || define('APP_ENV', 'production');
defined('SITENAME') || define(
    'SITENAME',
    'ASCB - Andres Soriano Colleges of Bislig'
);

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

ini_set('display_errors', APP_ENV === 'development' ? '1' : '0');
ini_set('log_errors', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_secure', $isHttps ? '1' : '0');
ini_set('session.cookie_samesite', 'Lax');

if (session_status() === PHP_SESSION_NONE) {
    session_name('ascb_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}
