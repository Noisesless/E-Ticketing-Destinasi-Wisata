<?php
require_once 'config/db.php';

// Get filter parameters
$type_filter = isset($_GET['type']) ? $_GET['type'] : 'all';

// Base query
$query = "SELECT * FROM transportations";
$params = [];

// Apply Type Filter
if ($type_filter != 'all') {
    $query .= " WHERE type = ?";
    $params[] = $type_filter;
}

// Default sort
$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$transportations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Render View
require 'views/desktop/transportation_list.php';
