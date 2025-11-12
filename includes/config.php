<?php
// ===============================
// Error reporting (development)
// ===============================
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error_log.txt');

// ===============================
// Session start
// ===============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===============================
// Database configuration
// ===============================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bantuinyuk_db');

// ===============================
// Create connection (MySQLi + try-catch)
// ===============================
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Set charset
    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    // Tampilkan pesan ringan (tanpa detail internal)
    die("Database connection error. Please try again later.");
}

// ===============================
// Base URL (fix ke root proyek)
// ===============================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
define('BASE_URL', $protocol . "://" . $host . "/miniprojek/bantuinyuk/");

// ===============================
// Helper functions
// ===============================

// Cek login user
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Cek role admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Redirect dengan keamanan
function redirect($url) {
    $fullUrl = BASE_URL . ltrim($url, '/');
    if (!headers_sent()) {
        header("Location: " . $fullUrl);
        exit();
    } else {
        echo "<script>window.location.href='" . $fullUrl . "';</script>";
        exit();
    }
}

// ===============================
// Security headers
// ===============================
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
?>
