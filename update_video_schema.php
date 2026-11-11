<?php
require 'config/db.php';

echo "<h1>Update Video Schema</h1>";

try {
    // Add 'youtube_url' column
    try {
        $pdo->exec("ALTER TABLE products ADD COLUMN youtube_url VARCHAR(255) DEFAULT NULL AFTER image");
        echo "Added 'youtube_url' column.<br>";
    } catch (PDOException $e) {
        // Column might already exist
        if (strpos($e->getMessage(), "Duplicate column name") !== false) {
             echo "Column 'youtube_url' already exists.<br>";
        } else {
             echo "Error adding column: " . $e->getMessage() . "<br>";
        }
    }

    echo "<h3>Schema Updated Successfully!</h3>";
    echo "<a href='index.php?page=admin_products'>Go to Admin Products</a>";

} catch (PDOException $e) {
    echo "<h3>Error:</h3> " . $e->getMessage();
}
