<?php
require 'config/db.php';

echo "<h1>Update Transportation Schema</h1>";

try {
    // Add 'facilities' column
    try {
        $pdo->exec("ALTER TABLE transportations ADD COLUMN facilities TEXT AFTER description");
        echo "Added 'facilities' column.<br>";
    } catch (PDOException $e) {
        // Column might already exist
    }

    // Add 'capacity' column
    try {
        $pdo->exec("ALTER TABLE transportations ADD COLUMN capacity INT DEFAULT 4 AFTER facilities");
        echo "Added 'capacity' column.<br>";
    } catch (PDOException $e) {
        // Column might already exist
    }

    // Update existing dummy data with some default values
    $pdo->exec("UPDATE transportations SET capacity = 6, facilities = 'AC, Musik, Reclining Seat' WHERE type = 'travel'");
    $pdo->exec("UPDATE transportations SET capacity = 7, facilities = 'AC, Bersih, Wangi, Supir Ramah' WHERE type = 'rental'");
    $pdo->exec("UPDATE transportations SET capacity = 1, facilities = 'Helm SNI, Masker, Jas Hujan' WHERE type = 'pickup'");

    echo "<h3>Schema Updated Successfully!</h3>";
    echo "<a href='index.php?page=admin_transportation'>Go to Admin Transportation</a>";

} catch (PDOException $e) {
    echo "<h3>Error:</h3> " . $e->getMessage();
}
