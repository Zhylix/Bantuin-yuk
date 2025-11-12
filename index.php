<?php
// Simple redirect to home page
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Build the redirect URL
$redirect_url = $protocol . "://" . $host . $base_path . "/pages/home.php";

// Redirect
header("Location: " . $redirect_url);
exit();
?>