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

$chartTypeLabels = [];
$chartTypeData = [];

$pdo->exec("
    CREATE TABLE IF NOT EXISTS pos_transaction_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ticket_id INT NULL,
        order_id INT NULL,
        pos_code VARCHAR(100),
        payment_category ENUM('online','offline') NOT NULL,
        amount DECIMAL(15,2) NOT NULL,
        entry_type VARCHAR(20),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$posUnits = [
    ['code' => 'POS-001 - Gerbang Utama', 'label' => 'POS Unit 1 - Gerbang Utama'],
    ['code' => 'POS-002 - Gerbang Timur', 'label' => 'POS Unit 2 - Gerbang Timur'],
    ['code' => 'POS-003 - Gerbang Barat', 'label' => 'POS Unit 3 - Gerbang Barat'],
    ['code' => 'POS-004 - Gerbang Selatan', 'label' => 'POS Unit 4 - Gerbang Selatan'],
    ['code' => 'POS-005 - Gerbang Utara', 'label' => 'POS Unit 5 - Gerbang Utara'],
];

$stmtPos = $pdo->query("
    SELECT 
        pos_code,
        payment_category,
        SUM(amount) AS total_amount,
        COUNT(id) AS total_entries
    FROM pos_transaction_logs
    WHERE DATE(created_at) = CURDATE()
    GROUP BY pos_code, payment_category
");
$posRows = $stmtPos->fetchAll(PDO::FETCH_ASSOC);

$posStatus = [];
foreach ($posRows as $row) {
    $code = $row['pos_code'] ?? '';
    if ($code === '') {
        continue;
    }
    if (!isset($posStatus[$code])) {
        $posStatus[$code] = [
            'online_amount' => 0,
            'online_entries' => 0,
            'offline_amount' => 0,
            'offline_entries' => 0,
        ];
    }
    $category = $row['payment_category'] ?? 'offline';
    if ($category === 'online') {
        $posStatus[$code]['online_amount'] += (float)($row['total_amount'] ?? 0);
        $posStatus[$code]['online_entries'] += (int)($row['total_entries'] ?? 0);
    } else {
        $posStatus[$code]['offline_amount'] += (float)($row['total_amount'] ?? 0);
        $posStatus[$code]['offline_entries'] += (int)($row['total_entries'] ?? 0);
    }
}

foreach ($posUnits as $unit) {
    $code = $unit['code'];
    $label = $unit['label'];
    $stats = $posStatus[$code] ?? [
        'online_amount' => 0,
        'online_entries' => 0,
        'offline_amount' => 0,
        'offline_entries' => 0,
    ];
    $totalEntries = $stats['online_entries'] + $stats['offline_entries'];
    $chartTypeLabels[] = $label;
    $chartTypeData[] = $totalEntries;
}

// Determine if we are inside the layout or need to load it
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <div class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-medium uppercase tracking-wide">
            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
            Panel Kendali Penjualan Tiket
        </div>
        <h1 class="mt-3 text-2xl md:text-3xl font-semibold text-slate-900">Dasbor Admin E-Tiket</h1>
        <p class="mt-1 text-sm text-slate-500">Ringkasan transaksi, tiket terjual, dan aktivitas terbaru sistem e-ticketing.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-white/60 border border-slate-200 rounded-full px-3 py-1 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Sistem Aktif
        </div>
        <a href="index.php?page=monitor" class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-slate-900 text-slate-50 hover:bg-slate-800 shadow-sm">
            <i class="fas fa-chart-line mr-2 text-emerald-300"></i>
            Buka Dashboard Monitoring Dinas
        </a>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm p-6 border border-emerald-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-emerald-600 tracking-wide uppercase">Total Pendapatan</p>
                <h3 class="text-2xl md:text-3xl font-semibold text-slate-900 mt-2"><?= formatRupiah($revenue) ?></h3>
            </div>
            <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 shadow-sm">
                <i class="fas fa-coins text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between text-xs">
            <div class="flex items-center text-emerald-700">
                <i class="fas fa-circle mr-1 text-[8px]"></i>
                <span>Transaksi berstatus dibayar</span>
            </div>
            <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Realtime</span>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm p-6 border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-sky-600 tracking-wide uppercase">Tiket Terjual</p>
                <h3 class="text-2xl md:text-3xl font-semibold text-slate-900 mt-2"><?= number_format($ticketsSold) ?></h3>
            </div>
            <div class="p-2.5 bg-sky-50 rounded-xl text-sky-600 shadow-sm">
                <i class="fas fa-ticket-alt text-xl"></i>
            </div>
        </div>
        <div class="mt-4 text-xs text-slate-500 flex items-center justify-between">
            <span>Total tiket valid dan digunakan.</span>
            <span class="px-2 py-0.5 rounded-full bg-slate-50 text-slate-600 border border-slate-200">Semua kanal</span>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm p-6 border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold text-indigo-600 tracking-wide uppercase">Total Pesanan</p>
                <h3 class="text-2xl md:text-3xl font-semibold text-slate-900 mt-2"><?= number_format($totalOrders) ?></h3>
            </div>
            <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600 shadow-sm">
                <i class="fas fa-receipt text-xl"></i>
            </div>
        </div>
        <div class="mt-4 text-xs text-slate-500 flex items-center justify-between">
            <span>Termasuk pesanan online dan loket.</span>
            <span class="px-2 py-0.5 rounded-full bg-slate-50 text-slate-600 border border-slate-200">Database</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="lg:col-span-2 bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Grafik Pendapatan 7 Hari Terakhir</h2>
                <p class="text-xs text-slate-500">Pergerakan total nominal transaksi per hari.</p>
            </div>
        </div>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Distribusi Tiket Masuk per POS</h2>
            <p class="text-xs text-slate-500 mb-4">Perbandingan jumlah tiket yang divalidasi tiap pos loket hari ini.</p>
            <div class="h-60 relative flex items-center justify-center">
                <canvas id="typeChart"></canvas>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 space-y-1">
            <div class="flex justify-between items-center">
                <span>Total POS Terhubung</span>
                <span class="font-medium text-slate-700"><?= count($posUnits) ?> Loket</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Monitoring Status POS Loket Hari Ini</h2>
            <p class="text-xs text-slate-500">Pemantauan transaksi online dan offline pada masing-masing pintu masuk.</p>
        </div>
        <span class="self-start md:self-auto text-xs px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
            <?= date('d M Y') ?>
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3">Nama POS Unit</th>
                    <th class="px-4 py-3">Status Sinkronisasi</th>
                    <th class="px-4 py-3 text-right">Tiket Online</th>
                    <th class="px-4 py-3 text-right">Nominal Online</th>
                    <th class="px-4 py-3 text-right">Tiket Offline</th>
                    <th class="px-4 py-3 text-right">Nominal Offline</th>
                    <th class="px-4 py-3 text-right">Total Tiket</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($posUnits as $unit): ?>
                    <?php
                    $code = $unit['code'];
                    $label = $unit['label'];
                    $stats = $posStatus[$code] ?? [
                        'online_amount' => 0,
                        'online_entries' => 0,
                        'offline_amount' => 0,
                        'offline_entries' => 0,
                    ];
                    $totalEntries = $stats['online_entries'] + $stats['offline_entries'];
                    $isActive = $totalEntries > 0;
                    ?>
                    <tr>
                        <td class="px-4 py-3 font-medium text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full <?= $isActive ? 'bg-emerald-500' : 'bg-slate-300' ?>"></span>
                            <span><?= htmlspecialchars($label) ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase <?= $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?>">
                                <?= $isActive ? 'Online Aktif' : 'Standby' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-slate-600"><?= number_format($stats['online_entries']) ?></td>
                        <td class="px-4 py-3 text-right text-slate-600"><?= formatRupiah($stats['online_amount']) ?></td>
                        <td class="px-4 py-3 text-right text-slate-600"><?= number_format($stats['offline_entries']) ?></td>
                        <td class="px-4 py-3 text-right text-slate-600"><?= formatRupiah($stats['offline_amount']) ?></td>
                        <td class="px-4 py-3 text-right font-semibold text-slate-800"><?= number_format($totalEntries) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="bg-white/80 backdrop-blur rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-base font-semibold text-slate-900">Pesanan Terbaru</h2>
            <p class="text-xs text-slate-500">Daftar transaksi paling mutakhir yang masuk ke sistem.</p>
        </div>
        <span class="text-xs text-slate-400">10 Transaksi Terakhir</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100 text-left text-xs">
            <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 font-semibold">ID Pesanan</th>
                    <th class="px-6 py-3 font-semibold">Pelanggan</th>
                    <th class="px-6 py-3 font-semibold">Total</th>
                    <th class="px-6 py-3 font-semibold">Status</th>
                    <th class="px-6 py-3 font-semibold">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                <?php 
                $recentOrders = $pdo->query("
                    SELECT o.*, u.full_name 
                    FROM orders o 
                    JOIN users u ON o.user_id = u.id 
                    ORDER BY o.created_at DESC 
                    LIMIT 10
                ")->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($recentOrders as $order): 
                ?>
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">#<?= htmlspecialchars($order['id']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-600"><?= htmlspecialchars($order['full_name']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-900 font-semibold"><?= formatRupiah($order['total_amount']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 inline-flex text-[11px] leading-4 font-semibold rounded-full 
                            <?= $order['status'] == 'paid' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' ?>">
                            <?= ucfirst(htmlspecialchars($order['status'])) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-400"><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: <?= json_encode($dates) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode($revenues) ?>,
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.08)',
                borderWidth: 2,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: '#059669',
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000).toLocaleString() + 'k';
                        }
                    }
                }
            }
        }
    });

    // POS Type Chart
    const ctxType = document.getElementById('typeChart').getContext('2d');
    new Chart(ctxType, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($chartTypeLabels) ?>,
            datasets: [{
                data: <?= json_encode($chartTypeData) ?>,
                backgroundColor: [
                    '#059669',
                    '#0284c7',
                    '#6366f1',
                    '#f59e0b',
                    '#84cc16'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
