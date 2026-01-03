<?php
require_once 'config.php';

// Add default result card settings if they don't exist
$defaults = [
    'contact_timing' => 'Monday - Saturday: 09:00 - 02:00',
    'contact_map_iframe' => 'https://maps.google.com/maps?q=Govt.%20Islamia%20Graduate%20College%20%28W%29%2C%20Cooper%20Road%2C%20Lahore&t=m&z=14&output=embed&iwloc=near'
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
