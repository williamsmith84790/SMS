<?php
require_once 'config.php';

// 1. Create site_stats table
$sql = "CREATE TABLE IF NOT EXISTS site_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon VARCHAR(50) NOT NULL,
    number VARCHAR(50) NOT NULL,
    label VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'site_stats' created successfully.<br>";
    // Insert demo data
    $conn->query("INSERT INTO site_stats (icon, number, label, sort_order) VALUES ('fas fa-calendar-alt', '34', 'The Year Founded', 1)");
    $conn->query("INSERT INTO site_stats (icon, number, label, sort_order) VALUES ('fas fa-user-graduate', '9000', 'Students In 2022', 2)");
    $conn->query("INSERT INTO site_stats (icon, number, label, sort_order) VALUES ('fas fa-chalkboard-teacher', '1500', 'Staff', 3)");
    $conn->query("INSERT INTO site_stats (icon, number, label, sort_order) VALUES ('fas fa-graduation-cap', '300000', 'Alumni', 4)");
    $conn->query("INSERT INTO site_stats (icon, number, label, sort_order) VALUES ('fas fa-handshake', '600', 'Partner', 5)");
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// 2. Add Feature Card settings to site_settings
$settings = [
    'feature_1_title' => 'Intermediate Program',
    'feature_1_text' => 'Comprehensive F.Sc, F.A, and I.Com programs designed to build a strong academic foundation.',
    'feature_1_link' => 'page.php?slug=program-intermediate',
    'feature_1_image' => '',
    'feature_2_title' => 'BS-4YDP Program',
    'feature_2_text' => 'Four-year degree programs offering in-depth specialization and research opportunities.',
    'feature_2_link' => 'page.php?slug=program-bs-4ydp',
    'feature_2_image' => ''
];

foreach ($settings as $key => $val) {
    $check = $conn->query("SELECT id FROM site_settings WHERE setting_key = '$key'");
    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)");
        $stmt->bind_param("ss", $key, $val);
        $stmt->execute();
        echo "Added setting: $key<br>";
    }
}

echo "Database updates completed.";
?>
