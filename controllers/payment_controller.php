<?php
if (!isLoggedIn()) {
    redirect('index.php?page=login');
}

$orderId = $_GET['id'] ?? null;
if (!$orderId) {
    redirect('index.php');
}

// Fetch Order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found or access denied.");
}

$action = $_GET['action'] ?? 'view';

// Handle Simulation Action
if ($action === 'simulate_bank_jateng') {
    require 'views/payment/bank_jateng_simulation.php';
    exit;
}

// Handle Payment Process (Simulated Callback)
if ($action === 'process_simulation') {
    try {
        $pdo->beginTransaction();

        // Update Order Status
        $stmt = $pdo->prepare("UPDATE orders SET status = 'paid', updated_at = NOW() WHERE id = ?");
        $stmt->execute([$orderId]);

        // Update Tickets Status
        // Find tickets linked to this order
        $stmtItems = $pdo->prepare("SELECT id FROM order_items WHERE order_id = ?");
        $stmtItems->execute([$orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_COLUMN);

        if ($items) {
            $inQuery = implode(',', array_fill(0, count($items), '?'));
            $stmtTickets = $pdo->prepare("UPDATE tickets SET status = 'valid' WHERE order_item_id IN ($inQuery)");
            $stmtTickets->execute($items);
        }

        $pdo->commit();
        
        // Redirect to Success Page
        header("Location: index.php?page=ticket&order_id=$orderId&payment_success=1");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Payment processing failed: " . $e->getMessage());
    }
}

// Default View: Payment Instruction / Status
require 'views/payment/index.php';
