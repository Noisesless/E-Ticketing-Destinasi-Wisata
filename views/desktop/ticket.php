<?php
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    // Show all my tickets if no order specified
    $stmt = $pdo->prepare("
        SELECT t.*, p.name_en, p.name_id, oi.visit_date 
        FROM tickets t
        JOIN order_items oi ON t.order_item_id = oi.id
        JOIN orders o ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY oi.visit_date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
} else {
    // Show specific order tickets
    $stmt = $pdo->prepare("
        SELECT t.*, p.name_en, p.name_id, oi.visit_date 
        FROM tickets t
        JOIN order_items oi ON t.order_item_id = oi.id
        JOIN orders o ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$orderId, $_SESSION['user_id']]);
}

$tickets = $stmt->fetchAll();

require 'views/layouts/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8"><?= trans('My Tickets', 'Tiket Saya') ?></h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($tickets as $ticket): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <div class="bg-teal-600 px-4 py-2">
                    <h3 class="text-white font-bold text-lg truncate"><?= trans($ticket['name_en'], $ticket['name_id']) ?></h3>
                </div>
                <div class="p-6 flex flex-col items-center">
                    <div class="mb-4 text-center">
                        <p class="text-gray-500 text-sm"><?= trans('Date', 'Tanggal') ?></p>
                        <p class="font-bold text-gray-800"><?= date('d M Y', strtotime($ticket['visit_date'])) ?></p>
                    </div>
                    
                    <div id="qrcode-<?= $ticket['id'] ?>" class="mb-4"></div>
                    <script type="text/javascript">
                        new QRCode(document.getElementById("qrcode-<?= $ticket['id'] ?>"), {
                            text: "<?= $ticket['ticket_code'] ?>",
                            width: 128,
                            height: 128
                        });
                    </script>
                    
                    <div class="text-center">
                        <p class="text-xs text-gray-400 font-mono"><?= $ticket['ticket_code'] ?></p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2 <?= $ticket['status'] == 'valid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= strtoupper($ticket['status']) ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if (empty($tickets)): ?>
            <p class="text-gray-500"><?= trans('No tickets found.', 'Tidak ada tiket ditemukan.') ?></p>
        <?php endif; ?>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
