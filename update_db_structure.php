<?php
require_once 'config.php';

// Add default result card settings if they don't exist
$defaults = [
    'result_card_board_name' => 'BOARD OF INTERMEDIATE & SECONDARY EDUCATION',
    'result_card_title' => 'RESULT INFORMATION',
    'result_card_exam_title' => 'HIGHER SECONDARY SCHOOL CERTIFICATE (ANNUAL), EXAMINATION'
];

echo "Updating site settings...<br>";

foreach ($defaults as $key => $value) {
    $check = $conn->query("SELECT id FROM site_settings WHERE setting_key = '$key'");
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO site_settings (setting_key, setting_value) VALUES ('$key', '$value')";
        if ($conn->query($sql)) {
            echo "Added setting: $key<br>";
        } else {
            echo "Error adding $key: " . $conn->error . "<br>";
        }
    } else {
        echo "Setting $key already exists.<br>";
    }
}

echo "Update complete.<br><a href='admin/settings.php'>Go to Settings</a>";
?>
