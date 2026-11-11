<?php
header('Content-Type: application/json');

if (!isOperator()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $code = $input['code'] ?? '';

    if (!$code) {
        echo json_encode(['success' => false, 'message' => 'No code provided']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT t.*, p.name_en, oi.visit_date FROM tickets t JOIN order_items oi ON t.order_item_id = oi.id JOIN products p ON oi.product_id = p.id WHERE t.ticket_code = ?");
    $stmt->execute([$code]);
    $ticket = $stmt->fetch();

    if (!$ticket) {
        echo json_encode(['success' => false, 'message' => 'Invalid Ticket']);
        exit;
    }

    if ($ticket['status'] === 'used') {
        echo json_encode(['success' => false, 'message' => 'Ticket Already Used at ' . $ticket['used_at'], 'ticket' => $ticket]);
        exit;
    }

    // Mark as used
    $update = $pdo->prepare("UPDATE tickets SET status = 'used', used_at = NOW() WHERE id = ?");
    $update->execute([$ticket['id']]);

    echo json_encode(['success' => true, 'message' => 'Access Granted', 'ticket' => $ticket]);
    exit;
}
