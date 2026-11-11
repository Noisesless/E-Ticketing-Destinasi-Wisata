<?php
if (!isLoggedIn()) {
    redirect('index.php?page=login');
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle remove
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
    redirect('index.php?page=cart');
}

require 'views/desktop/cart.php';
