<?php
/**
 * config.php — Database Configuration
 * =====================================
 * ASCB Website — Andres Soriano Colleges of Bislig
 *
 * HOW TO USE:
 *   1. Update the four constants below with your Namecheap/cPanel credentials.
 *   2. This file is included by both index.php and admin.php.
 *   3. Never commit this file to a public repository.
 *
 * NAMECHEAP TIP:
 *   In cPanel → MySQL Databases, the DB_USER is usually in the format:
 *   "cpanelusername_dbusername" (e.g. "ascb_dbuser").
 */

// ─── Database Credentials ──────────────────────────────────────────────────
define('DB_HOST', 'localhost');          // Almost always 'localhost' on shared hosting
define('DB_USER', 'root');              // Replace with your cPanel MySQL username
define('DB_PASS', '');                  // Replace with your cPanel MySQL password
define('DB_NAME', 'ascb_db');           // Replace with your actual database name
// ───────────────────────────────────────────────────────────────────────────

// Create the database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors and stop execution if any
if ($conn->connect_error) {
    // Show a friendly message rather than exposing server details
    die('<p style="font-family:sans-serif;color:#c0392b;padding:20px;">
        <strong>Database connection failed.</strong> Please check your config.php credentials.
        <br><small>Error: ' . htmlspecialchars($conn->connect_error) . '</small>
    </p>');
}

// Set character encoding to UTF-8 for proper display of all characters
$conn->set_charset('utf8mb4');
