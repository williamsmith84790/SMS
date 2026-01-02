<?php
require_once 'config.php';

// 1. Add link to ticker_items
$conn->query("ALTER TABLE ticker_items ADD COLUMN link VARCHAR(255) DEFAULT NULL");

// 2. Create events table
$conn->query("CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time VARCHAR(50),
    location VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// 3. Modify gallery_images
// Check if columns exist first or just run (MySQL will error if exists, but we can suppress or check)
// Simplest way: Add columns, if fail, assume they exist or handle error.
$conn->query("ALTER TABLE gallery_images ADD COLUMN media_type ENUM('image', 'video') DEFAULT 'image'");
$conn->query("ALTER TABLE gallery_images ADD COLUMN video_embed TEXT DEFAULT NULL");

echo "Database updates completed.";
?>
