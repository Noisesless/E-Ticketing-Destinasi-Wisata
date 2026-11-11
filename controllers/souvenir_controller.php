<?php
require_once 'config/db.php';

// WhatsApp Contact
$wa_number = env('WA_NUMBER', '6281234567890');

// Sorting
$sort_by = $_GET['sort'] ?? 'newest';
$query = "SELECT * FROM souvenirs";

switch ($sort_by) {
    case 'price_low':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY price DESC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$souvenirs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render View matching standard application layout
require 'views/desktop/souvenir_list.php';
