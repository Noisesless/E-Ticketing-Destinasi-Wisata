<?php
if (!isAdmin()) {
    redirect('index.php?page=login');
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle Image Upload securely
function uploadSouvenirImage($file) {
    $uploaded = secure_upload_image($file, 'img/produk/');
    return $uploaded ? 'produk/' . $uploaded : null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if ($action === 'store') {
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = uploadSouvenirImage($_FILES['image']);
        }
        
        $sql = "INSERT INTO souvenirs (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price, $image]);
        
        redirect('index.php?page=admin_souvenir&success=created');
        
    } elseif ($action === 'update' && $id) {
        $sql = "UPDATE souvenirs SET name=?, description=?, price=? WHERE id=?";
        $params = [$name, $description, $price, $id];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = uploadSouvenirImage($_FILES['image']);
            if ($image) {
                $sql = "UPDATE souvenirs SET name=?, description=?, price=?, image=? WHERE id=?";
                $params = [$name, $description, $price, $image, $id];
            }
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        redirect('index.php?page=admin_souvenir&success=updated');
    }
} elseif ($action === 'delete' && $id) {
    verify_csrf_token($_GET['_csrf_token'] ?? '');
    $stmt = $pdo->prepare("DELETE FROM souvenirs WHERE id = ?");
    $stmt->execute([$id]);
    redirect('index.php?page=admin_souvenir&success=deleted');
}

// Prepare data for view
$souvenir = null;
if (($action === 'edit' || $action === 'update') && $id) {
    $stmt = $pdo->prepare("SELECT * FROM souvenirs WHERE id = ?");
    $stmt->execute([$id]);
    $souvenir = $stmt->fetch(PDO::FETCH_ASSOC);
}

// List data
if ($action === 'list') {
    $stmt = $pdo->query("SELECT * FROM souvenirs ORDER BY created_at DESC");
    $souvenirs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// View Routing
if ($action === 'create' || $action === 'edit') {
    $content = 'views/admin/souvenir_form.php';
} else {
    $content = 'views/admin/souvenir_list.php';
}

// Include Admin Layout
if (!isset($layout_loaded)) {
    require 'views/layouts/admin_layout.php';
}
