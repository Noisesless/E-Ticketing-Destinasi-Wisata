<?php
require_once 'config/db.php';

// Get filter parameters
$type_filter = isset($_GET['type']) ? $_GET['type'] : 'all';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Base query
$query = "SELECT * FROM accommodations";
$params = [];

// Apply Type Filter
if ($type_filter != 'all') {
    $query .= " WHERE type = ?";
    $params[] = $type_filter;
}

// Apply Sorting
switch ($sort_by) {
    case 'price_low':
        $query .= " ORDER BY price_per_night ASC";
        break;
    case 'price_high':
        $query .= " ORDER BY price_per_night DESC";
        break;
    case 'rating':
        $query .= " ORDER BY rating DESC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render View
require 'views/desktop/accommodation_list.php';
