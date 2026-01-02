<?php
require_once 'config.php';

// Add permissions column to admins table
$sql = "ALTER TABLE admins ADD COLUMN permissions TEXT DEFAULT NULL";
if ($conn->query($sql) === TRUE) {
    echo "Column 'permissions' added successfully.<br>";
} else {
    echo "Error adding column: " . $conn->error . "<br>";
}

echo "Database updates completed.";
?>
