<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Function to check permissions
function has_permission($permission) {
    // ID 1 is Super Admin
    if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == 1) {
        return true;
    }

    if (!isset($_SESSION['admin_permissions'])) {
        return false;
    }

    $permissions = $_SESSION['admin_permissions'];

    // If permission string is stored as comma separated values
    if (is_string($permissions)) {
        $permissions_array = explode(',', $permissions);
        return in_array($permission, $permissions_array);
    }

    return false;
}
?>
