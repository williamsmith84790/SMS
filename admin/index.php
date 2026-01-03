<?php
// Redirect to dashboard or login
require_once '../config.php';
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}
exit;
?>
