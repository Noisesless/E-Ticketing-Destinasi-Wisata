<?php
require_once 'config/db.php';
require_once 'includes/functions.php';

// Check if ID is provided
if (!isset($_GET['id'])) {
    redirect('index.php?page=transportation');
}

$id = $_GET['id'];

// Fetch Detail
$stmt = $pdo->prepare("SELECT * FROM transportations WHERE id = ?");
$stmt->execute([$id]);
$transportation = $stmt->fetch(PDO::FETCH_ASSOC);

// If not found, redirect
if (!$transportation) {
    redirect('index.php?page=transportation');
}

// Generate WhatsApp Link
// Format: https://wa.me/NUMBER?text=MESSAGE
$wa_number = preg_replace('/[^0-9]/', '', $transportation['contact_number']);
// If number starts with 0, replace with 62
if (substr($wa_number, 0, 1) == '0') {
    $wa_number = '62' . substr($wa_number, 1);
}

$wa_message = "Halo, saya tertarik untuk memesan layanan transportasi: *" . $transportation['name'] . "* yang saya lihat di website Hollynice.";
$wa_link = "https://wa.me/" . $wa_number . "?text=" . urlencode($wa_message);


// Render View
require 'views/desktop/transportation_detail.php';
