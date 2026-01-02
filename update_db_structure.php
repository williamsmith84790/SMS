<?php
require_once 'config.php';

echo "<h2>Applying Database Updates...</h2>";

// 1. Add link column to urgent_alerts
$result = $conn->query("SHOW COLUMNS FROM urgent_alerts LIKE 'link'");
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE urgent_alerts ADD COLUMN link VARCHAR(255) DEFAULT NULL";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green'>&#10004; Added 'link' column to 'urgent_alerts' table.</p>";
    } else {
        echo "<p style='color:red'>&#10008; Error adding 'link' to 'urgent_alerts': " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:blue'>&#8505; 'link' column already exists in 'urgent_alerts'.</p>";
}

// 2. Add link column to notices
$result = $conn->query("SHOW COLUMNS FROM notices LIKE 'link'");
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE notices ADD COLUMN link VARCHAR(255) DEFAULT NULL";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green'>&#10004; Added 'link' column to 'notices' table.</p>";
    } else {
        echo "<p style='color:red'>&#10008; Error adding 'link' to 'notices': " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:blue'>&#8505; 'link' column already exists in 'notices'.</p>";
}

// 3. Add link column to ticker_items (if missing)
$result = $conn->query("SHOW COLUMNS FROM ticker_items LIKE 'link'");
if ($result->num_rows == 0) {
    $sql = "ALTER TABLE ticker_items ADD COLUMN link VARCHAR(255) DEFAULT NULL";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green'>&#10004; Added 'link' column to 'ticker_items' table.</p>";
    } else {
        echo "<p style='color:red'>&#10008; Error adding 'link' to 'ticker_items': " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:blue'>&#8505; 'link' column already exists in 'ticker_items'.</p>";
}

// 4. Add Gallery to Menu
// Check if it already exists to prevent duplicates
$result = $conn->query("SELECT * FROM menu_items WHERE label = 'Gallery' AND parent_id = 20");
if ($result->num_rows == 0) {
    // Find the next sort order
    $sort_res = $conn->query("SELECT MAX(sort_order) as max_sort FROM menu_items WHERE parent_id = 20");
    $row = $sort_res->fetch_assoc();
    $next_sort = ($row['max_sort'] ? $row['max_sort'] : 0) + 1;

    $sql = "INSERT INTO menu_items (label, link, parent_id, sort_order, location) VALUES ('Gallery', 'gallery.php', 20, $next_sort, 'header')";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green'>&#10004; Added 'Gallery' to 'Life at Campus' menu.</p>";
    } else {
        echo "<p style='color:red'>&#10008; Error adding 'Gallery' to menu: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color:blue'>&#8505; 'Gallery' menu item already exists.</p>";
}

echo "<hr><p><strong>Update Complete.</strong> You can delete this file now.</p>";
echo "<a href='index.php'>Go to Homepage</a>";
?>
