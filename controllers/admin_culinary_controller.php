<?php
if (!isAdmin()) {
    redirect('index.php?page=login');
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle Image Upload securely
function uploadCulinaryImage($file) {
    $uploaded = secure_upload_image($file, 'img/produk/');
    return $uploaded ? 'produk/' . $uploaded : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = $_POST['name'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $price_range = $_POST['price_range'];
    $opening_hours = $_POST['opening_hours'];
    $signature_dish = $_POST['signature_dish'];
    $rating = $_POST['rating'] ?? 0;

    if ($action === 'store') {
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = uploadCulinaryImage($_FILES['image']);
        }
        
        $sql = "INSERT INTO culinary_spots (name, description, location, price_range, opening_hours, signature_dish, rating, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $location, $price_range, $opening_hours, $signature_dish, $rating, $image]);
        
        redirect('index.php?page=admin_culinary&success=created');
        
    } elseif ($action === 'update' && $id) {
        $sql = "UPDATE culinary_spots SET name=?, description=?, location=?, price_range=?, opening_hours=?, signature_dish=?, rating=? WHERE id=?";
        $params = [$name, $description, $location, $price_range, $opening_hours, $signature_dish, $rating, $id];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = uploadCulinaryImage($_FILES['image']);
            if ($image) {
                $sql = "UPDATE culinary_spots SET name=?, description=?, location=?, price_range=?, opening_hours=?, signature_dish=?, rating=?, image=? WHERE id=?";
                $params = [$name, $description, $location, $price_range, $opening_hours, $signature_dish, $rating, $image, $id];
            }
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        redirect('index.php?page=admin_culinary&success=updated');
    }
} elseif ($action === 'delete' && $id) {
    verify_csrf_token($_GET['_csrf_token'] ?? '');
    $stmt = $pdo->prepare("DELETE FROM culinary_spots WHERE id = ?");
    $stmt->execute([$id]);
    redirect('index.php?page=admin_culinary&success=deleted');
}

// Prepare data for view
$culinary_spot = null;
if (($action === 'edit' || $action === 'update') && $id) {
    $stmt = $pdo->prepare("SELECT * FROM culinary_spots WHERE id = ?");
    $stmt->execute([$id]);
    $culinary_spot = $stmt->fetch(PDO::FETCH_ASSOC);
}

// List data
if ($action === 'list') {
    $stmt = $pdo->query("SELECT * FROM culinary_spots ORDER BY created_at DESC");
    $culinary_spots = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// View Routing
if ($action === 'create' || $action === 'edit') {
    $content = 'views/admin/culinary_form.php';
} else {
    $content = 'views/admin/culinary_list.php';
}

// Include Admin Layout
if (!isset($layout_loaded)) {
    require 'views/layouts/admin_layout.php';
}
