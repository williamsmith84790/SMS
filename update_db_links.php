<?php
require_once 'config.php';

echo "Updating database schema...\n";

// 1. Add 'link' column to urgent_alerts if not exists
$check = $conn->query("SHOW COLUMNS FROM urgent_alerts LIKE 'link'");
if ($check->num_rows == 0) {
    if ($conn->query("ALTER TABLE urgent_alerts ADD COLUMN link VARCHAR(255) DEFAULT NULL AFTER message")) {
        echo "Added 'link' column to urgent_alerts.\n";
    } else {
        echo "Error adding 'link' to urgent_alerts: " . $conn->error . "\n";
    }
} else {
    echo "'link' column already exists in urgent_alerts.\n";
}

// 2. Add 'link' column to notices if not exists
$check = $conn->query("SHOW COLUMNS FROM notices LIKE 'link'");
if ($check->num_rows == 0) {
    if ($conn->query("ALTER TABLE notices ADD COLUMN link VARCHAR(255) DEFAULT NULL AFTER file")) {
        echo "Added 'link' column to notices.\n";
    } else {
        echo "Error adding 'link' to notices: " . $conn->error . "\n";
    }
} else {
    echo "'link' column already exists in notices.\n";
}

// 3. Add 'Gallery' menu item under 'Life at Campus' (Parent ID 20)
// Check if it already exists
$check = $conn->query("SELECT id FROM menu_items WHERE label = 'Gallery' AND parent_id = 20");
if ($check->num_rows == 0) {
    // Determine sort order
    $order_res = $conn->query("SELECT MAX(sort_order) as max_order FROM menu_items WHERE parent_id = 20");
    $row = $order_res->fetch_assoc();
    $sort_order = $row['max_order'] + 1;

    $sql = "INSERT INTO menu_items (label, link, parent_id, sort_order, location) VALUES ('Gallery', 'gallery.php', 20, $sort_order, 'header')";
    if ($conn->query($sql)) {
        echo "Added 'Gallery' menu item.\n";
    } else {
        echo "Error adding menu item: " . $conn->error . "\n";
    }
} else {
    echo "'Gallery' menu item already exists.\n";
}

// 4. Check gallery tables just in case (Diagnostic for the reported error)
$res = $conn->query("SHOW TABLES LIKE 'gallery_albums'");
if ($res->num_rows > 0) {
    echo "gallery_albums table exists.\n";
} else {
    echo "WARNING: gallery_albums table MISSING!\n";
}

$res = $conn->query("SHOW TABLES LIKE 'videos'");
if ($res->num_rows > 0) {
    echo "videos table exists.\n";
} else {
    echo "WARNING: videos table MISSING!\n";
}

echo "Database update complete.\n";
?>
