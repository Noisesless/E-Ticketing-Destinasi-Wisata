<?php
require 'config/db.php';

// 1. Create Monitor User
$username = 'dinas';
$password = password_hash('password', PASSWORD_DEFAULT);
$role = 'monitor';
$email = 'dinas@example.com';
$fullname = 'Dinas Pariwisata & DPPKAD';

$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if (!$stmt->fetch()) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, full_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $role, $email, $fullname]);
    echo "User 'dinas' created (password: password)<br>";
} else {
    echo "User 'dinas' already exists<br>";
}

// 2. Seed Dummy Orders (Last 30 Days)
echo "Seeding dummy data...<br>";

// Get Products
$stmt = $pdo->query("SELECT id, price FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($products)) {
    die("No products found. Create products first.");
}

$faker_dates = [];
for ($i = 0; $i < 30; $i++) {
    $faker_dates[] = date('Y-m-d H:i:s', strtotime("-$i days"));
}

$pdo->beginTransaction();

try {
    for ($i = 0; $i < 50; $i++) {
        // Random Date
        $date = $faker_dates[array_rand($faker_dates)];
        
        // Random Product & Qty
        $product = $products[array_rand($products)];
        $qty = rand(1, 5);
        $total = $product['price'] * $qty;
        
        // Create Order
        $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method, transaction_id, created_at) VALUES (?, ?, 'paid', 'qris', ?, ?)");
        // Use user ID 1 (admin) or the monitor user for owner, doesn't matter much for stats
        $stmtOrder->execute([1, $total, 'TXN-DUMMY-' . uniqid(), $date]);
        $orderId = $pdo->lastInsertId();
        
        // Update created_at (since default might be CURRENT_TIMESTAMP)
        $pdo->prepare("UPDATE orders SET created_at = ? WHERE id = ?")->execute([$date, $orderId]);

        // Create Order Item
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, visit_date) VALUES (?, ?, ?, ?, ?)");
        $stmtItem->execute([$orderId, $product['id'], $qty, $product['price'], date('Y-m-d', strtotime($date))]);
        $itemId = $pdo->lastInsertId();

        // Create Tickets
        for ($j = 0; $j < $qty; $j++) {
            $stmtTicket = $pdo->prepare("INSERT INTO tickets (order_item_id, ticket_code, status, used_at) VALUES (?, ?, ?, ?)");
            // Randomly mark some as used
            $status = (rand(0, 1) == 1) ? 'used' : 'valid';
            $usedAt = ($status == 'used') ? $date : null;
            $stmtTicket->execute([$itemId, 'TIX-DUMMY-' . uniqid(), $status, $usedAt]);
        }
    }
    $pdo->commit();
    echo "Successfully seeded 50 dummy transactions.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
