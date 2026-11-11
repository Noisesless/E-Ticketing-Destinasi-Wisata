<?php
require_once 'config/db.php';

// Ensure user is admin
if (!isAdmin()) {
    redirect('index.php?page=login');
}

$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
        $stmt = $pdo->query("SELECT * FROM accommodations ORDER BY created_at DESC");
        $accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/admin/accommodation_list.php';
        break;

    case 'create':
        $accommodation = [
            'id' => '',
            'name' => '',
            'type' => 'hotel',
            'description' => '',
            'price_per_night' => '',
            'location' => '',
            'rating' => '4.0',
            'facilities' => '',
            'image' => ''
        ];
        $isEdit = false;
        require 'views/admin/accommodation_form.php';
        break;

    case 'store':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $name = $_POST['name'];
            $type = $_POST['type'];
            $description = $_POST['description'];
            $price = $_POST['price_per_night'];
            $location = $_POST['location'];
            $rating = $_POST['rating'];
            $facilities = $_POST['facilities'];
            
            // Secure Image Upload
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploaded = secure_upload_image($_FILES['image'], 'img/');
                if ($uploaded) {
                    $image = $uploaded;
                }
            }

            $stmt = $pdo->prepare("INSERT INTO accommodations (name, type, description, price_per_night, location, rating, facilities, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $type, $description, $price, $location, $rating, $facilities, $image]);
            
            redirect('index.php?page=admin_accommodation');
        }
        break;

    case 'edit':
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM accommodations WHERE id = ?");
        $stmt->execute([$id]);
        $accommodation = $stmt->fetch(PDO::FETCH_ASSOC);
        $isEdit = true;
        require 'views/admin/accommodation_form.php';
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $id = $_POST['id'];
            $name = $_POST['name'];
            $type = $_POST['type'];
            $description = $_POST['description'];
            $price = $_POST['price_per_night'];
            $location = $_POST['location'];
            $rating = $_POST['rating'];
            $facilities = $_POST['facilities'];
            
            // Get current image
            $stmt = $pdo->prepare("SELECT image FROM accommodations WHERE id = ?");
            $stmt->execute([$id]);
            $current_image = $stmt->fetchColumn();
            $image = $current_image;

            // Handle new image upload securely
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploaded = secure_upload_image($_FILES['image'], 'img/');
                if ($uploaded) {
                    $image = $uploaded;
                }
            }

            $stmt = $pdo->prepare("UPDATE accommodations SET name=?, type=?, description=?, price_per_night=?, location=?, rating=?, facilities=?, image=? WHERE id=?");
            $stmt->execute([$name, $type, $description, $price, $location, $rating, $facilities, $image, $id]);
            
            redirect('index.php?page=admin_accommodation');
        }
        break;

    case 'delete':
        verify_csrf_token($_GET['_csrf_token'] ?? '');
        $id = $_GET['id'];
        $stmt = $pdo->prepare("DELETE FROM accommodations WHERE id = ?");
        $stmt->execute([$id]);
        redirect('index.php?page=admin_accommodation');
        break;
}
