<?php
require_once 'config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM accommodations WHERE id = ?");
$stmt->execute([$id]);
$accommodation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$accommodation) {
    // Redirect or show 404
    header("Location: index.php?page=accommodation");
    exit;
}

// Render View
require 'views/desktop/accommodation_detail.php';
