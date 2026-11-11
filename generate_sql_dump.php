<?php
// generate_sql_dump.php
// Script untuk generate SQL Dump dari data lokal yang ada saat ini
// Agar bisa diimport ke Hosting

require_once 'config/db.php';

// Open file for writing
$fp = fopen('local_data_dump.sql', 'w');

// Function to write to file and screen
function output($fp, $text) {
    echo $text;
    fwrite($fp, $text);
}

// Fungsi helper untuk escape string SQL
function sql_escape($value, $pdo) {
    if ($value === null) return 'NULL';
    return $pdo->quote($value);
}

output($fp, "-- SQL Dump of Local Data\n");
output($fp, "-- Generated on " . date('Y-m-d H:i:s') . "\n\n");

// 1. Table: accommodations
output($fp, "-- Table: accommodations\n");
output($fp, "TRUNCATE TABLE accommodations;\n");
$stmt = $pdo->query("SELECT * FROM accommodations");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $values = array_map(function($val) use ($pdo) { return sql_escape($val, $pdo); }, array_values($row));
    output($fp, "INSERT INTO accommodations (" . implode(', ', array_keys($row)) . ") VALUES (" . implode(', ', $values) . ");\n");
}
output($fp, "\n");

// 2. Table: culinary_spots
output($fp, "-- Table: culinary_spots\n");
output($fp, "TRUNCATE TABLE culinary_spots;\n");
$stmt = $pdo->query("SELECT * FROM culinary_spots");
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $count++;
    $values = array_map(function($val) use ($pdo) { return sql_escape($val, $pdo); }, array_values($row));
    output($fp, "INSERT INTO culinary_spots (" . implode(', ', array_keys($row)) . ") VALUES (" . implode(', ', $values) . ");\n");
}
output($fp, "-- Rows exported: $count\n");
output($fp, "\n");

// 3. Table: souvenirs
output($fp, "-- Table: souvenirs\n");
output($fp, "TRUNCATE TABLE souvenirs;\n");
$stmt = $pdo->query("SELECT * FROM souvenirs");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $values = array_map(function($val) use ($pdo) { return sql_escape($val, $pdo); }, array_values($row));
    output($fp, "INSERT INTO souvenirs (" . implode(', ', array_keys($row)) . ") VALUES (" . implode(', ', $values) . ");\n");
}
output($fp, "\n-- Dump completed.");

fclose($fp);
