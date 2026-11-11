<?php
// Calculate Stats
$totalOrders = $pdo->query("SELECT count(*) FROM orders")->fetchColumn();
$ticketsSold = $pdo->query("SELECT count(*) FROM tickets")->fetchColumn();
$revenue = $pdo->query("SELECT sum(total_amount) FROM orders WHERE status='paid'")->fetchColumn() ?? 0;

// Chart Data: Last 7 Days Revenue
$dates = [];
$revenues = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = date('d M', strtotime($date));
    $dailyRev = $pdo->query("SELECT sum(total_amount) FROM orders WHERE status='paid' AND DATE(created_at) = '$date'")->fetchColumn() ?? 0;
    $revenues[] = $dailyRev;
}

// Chart Data: Ticket Types
$ticketTypes = $pdo->query("
    SELECT p.type, count(t.id) as count 
    FROM tickets t 
    JOIN order_items oi ON t.order_item_id = oi.id 
    JOIN products p ON oi.product_id = p.id 
    GROUP BY p.type
")->fetchAll(PDO::FETCH_KEY_PAIR);

$typeLabels = ['tour' => 'Paket Wisata', 'event' => 'Acara', 'entrance' => 'Tiket Masuk'];
$chartTypeLabels = [];
$chartTypeData = [];
foreach ($ticketTypes as $type => $count) {
    $chartTypeLabels[] = $typeLabels[$type] ?? $type;
    $chartTypeData[] = $count;
}

// Determine if we are inside the layout or need to load it
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1"><?= formatRupiah($revenue) ?></h3>
            </div>
            <div class="p-2 bg-teal-50 rounded-lg text-teal-600">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-green-600 flex items-center">
            <i class="fas fa-arrow-up mr-1"></i>
            <span>Update Realtime</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500">Tiket Terjual</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($ticketsSold) ?></h3>
            </div>
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <i class="fas fa-ticket-alt text-xl"></i>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-500">
            Total tiket valid
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($totalOrders) ?></h3>
            </div>
            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                <i class="fas fa-shopping-cart text-xl"></i>
            </div>
        </div>
         <div class="mt-4 text-sm text-gray-500">
            Semua status
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Tren Pendapatan (7 Hari Terakhir)</h3>
        <canvas id="revenueChart" height="200"></canvas>
    </div>

    <!-- Ticket Type Chart -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Distribusi Penjualan Tiket</h3>
        <div class="h-64 flex justify-center">
            <canvas id="typeChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Pesanan Terbaru</h3>
        <a href="#" class="text-sm text-teal-600 hover:text-teal-700 font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-3 font-medium">ID Pesanan</th>
                    <th class="px-6 py-3 font-medium">Pelanggan</th>
                    <th class="px-6 py-3 font-medium">Total</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $orders = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY created_at DESC LIMIT 5")->fetchAll();
                foreach ($orders as $order):
                    $statusColors = [
                        'paid' => 'bg-green-50 text-green-700 border-green-200',
                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'cancelled' => 'bg-red-50 text-red-700 border-red-200'
                    ];
                    $statusLabel = [
                        'paid' => 'Dibayar',
                        'pending' => 'Menunggu',
                        'cancelled' => 'Dibatalkan'
                    ];
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">#<?= $order['id'] ?></td>
                    <td class="px-6 py-4 text-sm text-gray-600"><?= $order['username'] ?></td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= formatRupiah($order['total_amount']) ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusColors[$order['status']] ?? 'bg-gray-100 text-gray-800' ?>">
                            <?= $statusLabel[$order['status']] ?? $order['status'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Revenue Chart
    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: <?= json_encode($dates) ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?= json_encode($revenues) ?>,
                borderColor: '#0d9488',
                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    // Type Chart
    const ctxType = document.getElementById('typeChart').getContext('2d');
    new Chart(ctxType, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($chartTypeLabels) ?>,
            datasets: [{
                data: <?= json_encode($chartTypeData) ?>,
                backgroundColor: ['#0d9488', '#f97316', '#3b82f6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
