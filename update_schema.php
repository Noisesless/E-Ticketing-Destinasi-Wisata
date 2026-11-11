<?php
require_once 'config/db.php';

echo "<h2>Updating Database Schema...</h2>";

try {
    // Add new columns to products table
    $columns = [
        "ADD COLUMN IF NOT EXISTS duration VARCHAR(100) AFTER type",
        "ADD COLUMN IF NOT EXISTS itinerary_en TEXT AFTER duration",
        "ADD COLUMN IF NOT EXISTS itinerary_id TEXT AFTER itinerary_en",
        "ADD COLUMN IF NOT EXISTS facilities_en TEXT AFTER itinerary_id",
        "ADD COLUMN IF NOT EXISTS facilities_id TEXT AFTER facilities_en",
        "ADD COLUMN IF NOT EXISTS policy_en TEXT AFTER facilities_id",
        "ADD COLUMN IF NOT EXISTS policy_id TEXT AFTER policy_en",
        "ADD COLUMN IF NOT EXISTS rating DECIMAL(3, 1) DEFAULT 5.0 AFTER policy_id",
        "ADD COLUMN IF NOT EXISTS review_count INT DEFAULT 0 AFTER rating"
    ];

    foreach ($columns as $sql) {
        try {
            $pdo->exec("ALTER TABLE products $sql");
            echo "Executed: ALTER TABLE products $sql <br>";
        } catch (PDOException $e) {
            // Ignore if column exists (though IF NOT EXISTS should handle it in newer MariaDB/MySQL)
            echo "Skipped/Error: " . $e->getMessage() . "<br>";
        }
    }

    echo "<h3>Schema Update Complete!</h3>";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
