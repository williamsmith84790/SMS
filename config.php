<?php
// config.php - Database connection configuration

// Default XAMPP settings
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASSWORD') ?: '';
$db_name = getenv('DB_NAME') ?: 'edu_portal';
$db_port = getenv('DB_PORT') ?: 3306;

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Helper function to get base URL
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['PHP_SELF']);
    // Remove trailing slash if exists
    $script = rtrim($script, '/');
    // If script is just root /, handle it
    if ($script == DIRECTORY_SEPARATOR) $script = '';
    return "$protocol://$host$script";
}

// Global configuration constants
define('SITE_NAME', 'EduPortal');
?>
