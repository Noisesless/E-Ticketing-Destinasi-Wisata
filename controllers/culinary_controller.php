<?php
require_once 'config/db.php';

// Check if detail view is requested
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM culinary_spots WHERE id = ?");
    $stmt->execute([$id]);
    $culinary = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$culinary) {
        // Redirect or show 404
        redirect('index.php?page=culinary');
    }

    require 'views/desktop/culinary_detail.php';
    exit;
}

// Get sort/filter parameters (optional, keeping it simple for now)
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'rating';

// Base query
$query = "SELECT * FROM culinary_spots";

// Apply Sorting
switch ($sort_by) {
    case 'rating':
        $query .= " ORDER BY rating DESC";
        break;
    case 'newest':
        $query .= " ORDER BY created_at DESC";
        break;
    default:
        $query .= " ORDER BY rating DESC";
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute();
$culinary_spots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render View
require 'views/desktop/culinary_list.php';
