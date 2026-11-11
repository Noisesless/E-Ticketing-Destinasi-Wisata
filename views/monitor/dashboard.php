<?php
// Determine date for header
$currentDate = date('l, d F Y');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring PAD - BPPKAD Wonosobo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-white/80 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center gap-4">
                        <div class="flex items-center">
                            <img src="img/Lambang_Kabupaten_Wonosobo (1).webp" alt="Lambang Kabupaten Wonosobo" class="h-10 w-10 rounded-full bg-white/90 p-1 shadow mr-3">
                            <div class="hidden sm:block h-10 w-px bg-slate-200 mr-3"></div>
                        </div>
                        <div>
                            <div class="text-[11px] font-semibold tracking-wide text-emerald-700 uppercase">Pemerintah Kabupaten Wonosobo</div>
                            <h1 class="text-lg md:text-xl font-semibold text-slate-900 leading-tight">Dashboard Monitoring PAD E-Ticketing</h1>
                            <p class="text-xs text-slate-500">BPPKAD & Dinas Pariwisata Kabupaten Wonosobo</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-xs text-slate-500"><?= $currentDate ?></span>
                        <span class="text-[11px] font-medium text-emerald-600 flex items-center justify-end gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Online Gateway Bank Jateng
                        </span>
                    </div>
                    <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                    <a href="index.php?page=logout" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                        <i class="fas fa-arrow-right-from-bracket mr-1.5"></i>
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header Actions & Period Filter -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-semibold text-slate-900">Rekapitulasi Penerimaan Retribusi Wisata</h2>
                <p class="text-xs md:text-sm text-slate-500 mt-1">Data agregat transaksi tiket online & offline untuk transparansi Pendapatan Asli Daerah (PAD).</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs">
                    <button type="button" class="px-3 py-1.5 rounded-lg bg-white font-medium text-slate-800 shadow-sm">Real-time</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800">Harian</button>
                    <button type="button" class="px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-800">Bulanan</button>
                </div>
                <a href="index.php?page=monitor&export=pdf&mode=daily&date=<?= urlencode(date('Y-m-d')) ?>" target="_blank" class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-emerald-600 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700">
                    <i class="fas fa-file-pdf mr-1.5"></i>
                    Export PDF
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold tracking-wider text-emerald-700 uppercase">Total PAD Terhimpun</span>
                        <span class="p-2 rounded-xl bg-emerald-50 text-emerald-600 text-sm">
                            <i class="fas fa-wallet"></i>
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-2xl lg:text-3xl font-bold text-slate-900">
                            <?= formatRupiah($summary['total_revenue'] ?? 0) ?>
                        </span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Status Transaksi</span>
                    <span class="inline-flex items-center text-emerald-600 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                        PAID / Terverifikasi
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold tracking-wider text-sky-700 uppercase">Tiket Terdistribusi</span>
                        <span class="p-2 rounded-xl bg-sky-50 text-sky-600 text-sm">
                            <i class="fas fa-ticket-simple"></i>
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-2xl lg:text-3xl font-bold text-slate-900">
                            <?= number_format($ticketSummary['total_tickets'] ?? 0) ?>
                        </span>
                        <span class="text-xs text-slate-400 ml-1">lembar tiket</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Validitas Tiket</span>
                    <span class="inline-flex items-center text-sky-600 font-medium">
                        <i class="fas fa-circle-check mr-1.5 text-xs"></i>
                        Status Valid / Used
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold tracking-wider text-indigo-700 uppercase">Volume Transaksi</span>
                        <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600 text-sm">
                            <i class="fas fa-receipt"></i>
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-2xl lg:text-3xl font-bold text-slate-900">
                            <?= number_format($summary['total_transactions'] ?? 0) ?>
                        </span>
                        <span class="text-xs text-slate-400 ml-1">order sukses</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Kanal Penjualan</span>
                    <span class="inline-flex items-center text-slate-700 font-medium">
                        Online + POS Loket
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold tracking-wider text-amber-700 uppercase">Sinkronisasi Bank</span>
                        <span class="p-2 rounded-xl bg-amber-50 text-amber-600 text-sm">
                            <i class="fas fa-building-columns"></i>
                        </span>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-semibold text-slate-800">Rekening Kas Daerah</span>
                        <p class="text-xs text-slate-500 mt-1">Bank Jateng Cabang Wonosobo</p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Status Rekonsiliasi</span>
                    <span class="inline-flex items-center text-emerald-600 font-medium">
                        <i class="fas fa-arrows-rotate mr-1.5 text-xs animate-spin-slow"></i>
                        Sinkron Otomatis
                    </span>
                </div>
            </div>
        </div>

        <!-- POS Settlement Today Table -->
        <?php if (!empty($posSettlementsToday)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
            <h3 class="text-base font-semibold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fas fa-cash-register text-emerald-600"></i>
                Rekonsiliasi Loket POS Hari Ini
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Unit POS</th>
                            <th class="px-4 py-3">Kategori Pembayaran</th>
                            <th class="px-4 py-3 text-right">Jumlah Transaksi</th>
                            <th class="px-4 py-3 text-right">Total Penerimaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($posSettlementsToday as $pos): ?>
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-800"><?= htmlspecialchars($pos['pos_code'] ?? 'POS Loket') ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase <?= ($pos['payment_category'] ?? '') === 'online' ? 'bg-sky-50 text-sky-700' : 'bg-amber-50 text-amber-700' ?>">
                                    <?= htmlspecialchars($pos['payment_category'] ?? 'offline') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-slate-600"><?= number_format($pos['total_entries'] ?? 0) ?></td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800"><?= formatRupiah($pos['total_amount'] ?? 0) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Visual Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Line Chart: Daily Revenue Trend -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Tren Penerimaan Harian (30 Hari Terakhir)</h3>
                        <p class="text-xs text-slate-500">Pergerakan total nominal transaksi per hari.</p>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart: Distribution by Ticket Type -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Distribusi Produk Tiket</h3>
                    <p class="text-xs text-slate-500 mb-4">Porsi pendapatan berdasarkan jenis layanan wisata.</p>
                    <div class="h-60 relative flex items-center justify-center">
                        <canvas id="ticketTypeChart"></canvas>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-500 space-y-1">
                    <?php foreach ($salesByType as $row): ?>
                        <div class="flex justify-between items-center">
                            <span class="capitalize"><?= htmlspecialchars($row['type']) ?></span>
                            <span class="font-medium text-slate-700"><?= formatRupiah($row['revenue']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </main>

    <script>
        // Line Chart: Daily Revenue
        const ctxTrend = document.getElementById('revenueTrendChart').getContext('2d');
        new Chart(ctxTrend, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($revenueData) ?>,
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
                                return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                            }
                        }
                    }
                }
            }
        });

        // Doughnut Chart: Sales by Type
        const ctxType = document.getElementById('ticketTypeChart').getContext('2d');
        const typeLabels = <?= json_encode(array_column($salesByType, 'type')) ?>;
        const typeData = <?= json_encode(array_column($salesByType, 'revenue')) ?>;

        new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: typeLabels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    data: typeData,
                    backgroundColor: ['#059669', '#0284c7', '#6366f1', '#f59e0b'],
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
</body>
</html>
