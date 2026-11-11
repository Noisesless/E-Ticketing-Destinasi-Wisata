<?php
if (!isAdmin()) {
    redirect('index.php?page=login');
}

$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Handle Image Upload (Secured with whitelist & MIME check)
function uploadImage($file) {
    return secure_upload_image($file, 'img/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    // Common Fields
    $name_en = $_POST['name_en'];
    $name_id = $_POST['name_id'];
    $youtube_url = $_POST['youtube_url'] ?? null;
    $desc_en = $_POST['description_en'];
    $desc_id = $_POST['description_id'];
    $price = $_POST['price'];
    $type = $_POST['type'];
    $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : null;

    // Tour Fields
    $duration = $_POST['duration'] ?? '';
    $itinerary_en = $_POST['itinerary_en'] ?? '';
    $itinerary_id = $_POST['itinerary_id'] ?? '';
    $facilities_en = $_POST['facilities_en'] ?? '';
    $facilities_id = $_POST['facilities_id'] ?? '';
    $policy_en = $_POST['policy_en'] ?? '';
    $policy_id = $_POST['policy_id'] ?? '';

    // Event Fields
    $tagline_en = $_POST['tagline_en'] ?? '';
    $tagline_id = $_POST['tagline_id'] ?? '';
    $event_date = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
    $event_end_date = !empty($_POST['event_end_date']) ? $_POST['event_end_date'] : null;
    $meeting_point_en = $_POST['meeting_point_en'] ?? '';
    $meeting_point_id = $_POST['meeting_point_id'] ?? '';
    $accessibility_en = $_POST['accessibility_en'] ?? '';
    $accessibility_id = $_POST['accessibility_id'] ?? '';
    $what_to_bring_en = $_POST['what_to_bring_en'] ?? '';
    $what_to_bring_id = $_POST['what_to_bring_id'] ?? '';
    $quota = !empty($_POST['quota']) ? $_POST['quota'] : 100;

    // Entrance Fields
    $opening_hours_en = $_POST['opening_hours_en'] ?? '';
    $opening_hours_id = $_POST['opening_hours_id'] ?? '';
    $address_en = $_POST['address_en'] ?? '';
    $address_id = $_POST['address_id'] ?? '';
    $exclusions_en = $_POST['exclusions_en'] ?? '';
    $exclusions_id = $_POST['exclusions_id'] ?? '';
    $terms_en = $_POST['terms_en'] ?? '';
    $terms_id = $_POST['terms_id'] ?? '';
    $contact_phone = $_POST['contact_phone'] ?? '';
    $contact_email = $_POST['contact_email'] ?? '';

    if ($action === 'store') {
        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image = uploadImage($_FILES['image']);
        }
        
        $sql = "INSERT INTO products (
            name_en, name_id, youtube_url, description_en, description_id, price, type, image, 
            duration, itinerary_en, itinerary_id, facilities_en, facilities_id, policy_en, policy_id, 
            latitude, longitude,
            tagline_en, tagline_id, event_date, event_end_date, meeting_point_en, meeting_point_id, 
            accessibility_en, accessibility_id, what_to_bring_en, what_to_bring_id, quota,
            opening_hours_en, opening_hours_id, address_en, address_id, exclusions_en, exclusions_id, 
            terms_en, terms_id, contact_phone, contact_email
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, ?, 
            ?, ?,
            ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $name_en, $name_id, $youtube_url, $desc_en, $desc_id, $price, $type, $image,
            $duration, $itinerary_en, $itinerary_id, $facilities_en, $facilities_id, $policy_en, $policy_id,
            $latitude, $longitude,
            $tagline_en, $tagline_id, $event_date, $event_end_date, $meeting_point_en, $meeting_point_id,
            $accessibility_en, $accessibility_id, $what_to_bring_en, $what_to_bring_id, $quota,
            $opening_hours_en, $opening_hours_id, $address_en, $address_id, $exclusions_en, $exclusions_id,
            $terms_en, $terms_id, $contact_phone, $contact_email
        ]);
        
        $productId = $pdo->lastInsertId();

        // Handle Gallery
        if (isset($_FILES['gallery'])) {
            $count = count($_FILES['gallery']['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['gallery']['error'][$i] == 0) {
                    $file = [
                        'name' => $_FILES['gallery']['name'][$i],
                        'tmp_name' => $_FILES['gallery']['tmp_name'][$i]
                    ];
                    $imgPath = uploadImage($file);
                    if ($imgPath) {
                        $stmtImg = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                        $stmtImg->execute([$productId, $imgPath]);
                    }
                }
            }
        }
        
        redirect('index.php?page=admin_products');
    } 
    elseif ($action === 'update') {
        $sql = "UPDATE products SET 
            name_en=?, name_id=?, youtube_url=?, description_en=?, description_id=?, price=?, type=?, 
            duration=?, itinerary_en=?, itinerary_id=?, facilities_en=?, facilities_id=?, policy_en=?, policy_id=?, 
            latitude=?, longitude=?,
            tagline_en=?, tagline_id=?, event_date=?, event_end_date=?, meeting_point_en=?, meeting_point_id=?, 
            accessibility_en=?, accessibility_id=?, what_to_bring_en=?, what_to_bring_id=?, quota=?,
            opening_hours_en=?, opening_hours_id=?, address_en=?, address_id=?, exclusions_en=?, exclusions_id=?, 
            terms_en=?, terms_id=?, contact_phone=?, contact_email=?
        ";
        
        $params = [
            $name_en, $name_id, $youtube_url, $desc_en, $desc_id, $price, $type,
            $duration, $itinerary_en, $itinerary_id, $facilities_en, $facilities_id, $policy_en, $policy_id,
            $latitude, $longitude,
            $tagline_en, $tagline_id, $event_date, $event_end_date, $meeting_point_en, $meeting_point_id,
            $accessibility_en, $accessibility_id, $what_to_bring_en, $what_to_bring_id, $quota,
            $opening_hours_en, $opening_hours_id, $address_en, $address_id, $exclusions_en, $exclusions_id,
            $terms_en, $terms_id, $contact_phone, $contact_email
        ];

        // Check if new image uploaded
        $imageUpdated = false;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $newImage = uploadImage($_FILES['image']);
            if ($newImage) {
                $sql .= ", image=? WHERE id=?";
                $params[] = $newImage;
                $params[] = $id;
                $imageUpdated = true;
            }
        }
        
        if (!$imageUpdated) {
            $sql .= " WHERE id=?";
            $params[] = $id;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Handle Gallery (Append)
        if (isset($_FILES['gallery'])) {
            $count = count($_FILES['gallery']['name']);
            for ($i = 0; $i < $count; $i++) {
                if ($_FILES['gallery']['error'][$i] == 0) {
                    $file = [
                        'name' => $_FILES['gallery']['name'][$i],
                        'tmp_name' => $_FILES['gallery']['tmp_name'][$i]
                    ];
                    $imgPath = uploadImage($file);
                    if ($imgPath) {
                        $stmtImg = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                        $stmtImg->execute([$id, $imgPath]);
                    }
                }
            }
        }
        
        redirect('index.php?page=admin_products');
    }
}

if ($action === 'delete') {
    verify_csrf_token($_GET['_csrf_token'] ?? '');
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    redirect('index.php?page=admin_products');
}

// Fetch data for views
if ($action === 'edit') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    // Fetch gallery
    $stmtImg = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ?");
    $stmtImg->execute([$id]);
    $gallery = $stmtImg->fetchAll();
    
    require 'views/admin/product_form.php';
} elseif ($action === 'create') {
    require 'views/admin/product_form.php';
} else {
    // List
    $products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
    require 'views/admin/product_list.php';
}
