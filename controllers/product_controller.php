<?php
$id = $_GET['id'] ?? null;

if (!$id) {
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found";
    exit;
}

// Fetch Gallery Images
$stmtImg = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
$stmtImg->execute([$id]);
$gallery = $stmtImg->fetchAll();

// Check if adding to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isLoggedIn()) {
        redirect('index.php?page=login');
    }
    
    // Simple cart session storage
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $qty = (int)$_POST['quantity'];
    $date = $_POST['visit_date'];
    
    // Add to cart array
    $_SESSION['cart'][] = [
        'product_id' => $product['id'],
        'name' => trans($product['name_en'], $product['name_id']),
        'price' => $product['price'],
        'quantity' => $qty,
        'visit_date' => $date,
        'image' => $product['image']
    ];
    
    redirect('index.php?page=cart');
}

if (isMobileDevice()) {
    require 'views/mobile/product_detail.php';
} else {
    require 'views/desktop/product_detail.php';
}
