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
defined('SITENAME') || define(
    'SITENAME',
    'ASCB - Andres Soriano Colleges of Bislig'
);
