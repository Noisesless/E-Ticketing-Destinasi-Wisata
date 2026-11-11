<?php
if (!isLoggedIn()) {
    redirect('index.php?page=login');
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect('index.php');
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    try {
        $pdo->beginTransaction();

        $paymentMethod = $_POST['payment_method'] ?? 'qris';
        if ($paymentMethod === 'va' && isset($_POST['va_bank'])) {
            $paymentMethod = 'va_' . $_POST['va_bank'];
        }

        // 1. Create Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status, payment_method, transaction_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $total, 'pending', $paymentMethod, 'TXN-' . time()]); // Pending payment
        $orderId = $pdo->lastInsertId();

        // 2. Create Items & Tickets
        foreach ($cart as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, visit_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price'], $item['visit_date']]);
            $itemId = $pdo->lastInsertId();

            // Generate Tickets
            for ($i = 0; $i < $item['quantity']; $i++) {
                $ticketCode = strtoupper(uniqid('TIX-'));
                $stmtTicket = $pdo->prepare("INSERT INTO tickets (order_item_id, ticket_code, status) VALUES (?, ?, 'pending_payment')");
                $stmtTicket->execute([$itemId, $ticketCode]);
            }
        }

        $pdo->commit();
        unset($_SESSION['cart']);
        
        // Redirect to Payment Page
        header("Location: index.php?page=payment&id=$orderId");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die($e->getMessage());
    }
}

require 'views/desktop/checkout.php';
