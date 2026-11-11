<?php
header('Content-Type: application/json');
require '../config/db.php';

// Auth Guard: Only authenticated operators/admins OR valid API Key holders (SP-006)
$isAuthorized = false;
if (isOperator()) {
    $isAuthorized = true;
} else {
    $headers = function_exists('getallheaders') ? getallheaders() : [];
    $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? '');
    $apiKey = env('API_SECRET_KEY');
    if (!empty($apiKey) && !empty($authHeader) && hash_equals("Bearer $apiKey", $authHeader)) {
        $isAuthorized = true;
    }
}

if (!$isAuthorized) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Akses ditolak. Hanya operator atau API resmi yang diizinkan.']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$code = $input['ticket_code'] ?? '';

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Kode tiket tidak boleh kosong.']);
    exit;
}

try {
    // Find ticket
    $stmt = $pdo->prepare("
        SELECT t.*, oi.visit_date, p.name_en, p.name_id, o.status as order_status 
        FROM tickets t
        JOIN order_items oi ON t.order_item_id = oi.id
        JOIN orders o ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE t.ticket_code = ?
    ");
    $stmt->execute([$code]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo json_encode(['success' => false, 'message' => 'Tiket tidak ditemukan.']);
        exit;
    }

    // Check Order Status
    if ($ticket['order_status'] !== 'paid') {
        echo json_encode(['success' => false, 'message' => 'Tiket belum dibayar (Status Pesanan: ' . $ticket['order_status'] . ').']);
        exit;
    }

    // Check Ticket Status
    if ($ticket['status'] === 'pending_payment') {
         echo json_encode(['success' => false, 'message' => 'Tiket belum aktif (Menunggu Pembayaran).']);
         exit;
    }

    if ($ticket['status'] === 'used') {
        $usedTime = date('d M Y H:i', strtotime($ticket['used_at']));
        echo json_encode([
            'success' => false, 
            'message' => "Tiket sudah digunakan pada $usedTime."
        ]);
        exit;
    }

    // Validate Visit Date (Optional: only allow entry on visit date?)
    // For now, we assume flexible or strict date checking depending on business rule.
    // Let's implement a simple check: if today is > visit_date, maybe expired?
    // But for simplicity in this demo, we just check status.
    
    // Mark as used
    $updateStmt = $pdo->prepare("UPDATE tickets SET status = 'used', used_at = NOW() WHERE id = ?");
    $updateStmt->execute([$ticket['id']]);

    echo json_encode([
        'success' => true,
        'ticket' => [
            'ticket_code' => $ticket['ticket_code'],
            'name_en' => $ticket['name_en'],
            'name_id' => $ticket['name_id'],
            'visit_date' => date('d M Y', strtotime($ticket['visit_date'])),
            'status' => 'valid'
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server.']);
}
