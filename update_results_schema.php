<?php
require_once 'config.php';

// Add new columns to student_results table
$alter_queries = [
    "ALTER TABLE student_results ADD COLUMN class VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE student_results ADD COLUMN part VARCHAR(50) DEFAULT NULL",
    "ALTER TABLE student_results ADD COLUMN institution VARCHAR(255) DEFAULT NULL",
    "ALTER TABLE student_results ADD COLUMN registration_number VARCHAR(100) DEFAULT NULL",
    "ALTER TABLE student_results ADD COLUMN result_status VARCHAR(50) DEFAULT NULL" // Pass/Fail/Compart
];

foreach ($alter_queries as $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Query successful: $sql<br>";
    } else {
        // Ignore "Duplicate column name" error (Code 1060)
        if ($conn->errno == 1060) {
            echo "Column already exists (ignored): $sql<br>";
        } else {
            echo "Error: " . $conn->error . "<br>";
        }
    }
}

echo "Database schema updated.";
?>
