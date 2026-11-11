<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Penjualan Tiket - Dinas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-4">
                        <i class="fas fa-building-columns text-3xl text-gray-700"></i>
                        <div class="border-l border-gray-300 h-10 mx-2"></div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 leading-tight">Dashboard Monitoring</h1>
                            <p class="text-xs text-gray-500">DPPKAD & Dinas Pariwisata</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-medium text-gray-900"><?= $_SESSION['username'] ?? 'Dinas User' ?></p>
                        <p class="text-xs text-green-600 font-semibold"><i class="fas fa-circle text-[8px] mr-1"></i> Live Data</p>
                    </div>
                    <a href="index.php?page=logout" class="bg-gray-100 hover:bg-gray-200 text-gray-600 p-2 rounded-full transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Revenue -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-teal-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-teal-100 rounded-md p-3">
                            <i class="fas fa-coins text-teal-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Pendapatan (PAD)</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= formatRupiah($summary['total_revenue'] ?? 0) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Tickets -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Tiket Terjual</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= number_format($ticketSummary['total_tickets'] ?? 0) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-indigo-500">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                            <i class="fas fa-file-invoice text-indigo-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Transaksi</dt>
                                <dd class="text-2xl font-bold text-gray-900"><?= number_format($summary['total_transactions'] ?? 0) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Line Chart: Daily Revenue -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Tren Pendapatan (30 Hari Terakhir)</h3>
                    <button onclick="window.print()" class="text-sm text-teal-600 hover:text-teal-800"><i class="fas fa-download mr-1"></i> Export</button>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Pie Chart: Ticket Types -->
            <div class="bg-white shadow rounded-lg p-6">
                 <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Penjualan Tiket</h3>
                 <div class="relative h-64 w-full flex justify-center">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recap Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Rekapitulasi Penjualan</h3>
                <span class="text-xs text-gray-500">Data Real-time</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Tiket</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terjual</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($salesByType as $row): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                <?= $row['type'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                <?= number_format($row['count']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-gray-900">
                                <?= formatRupiah($row['revenue']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900">Total</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                <?= number_format($ticketSummary['total_tickets'] ?? 0) ?>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-teal-600">
                                <?= formatRupiah($summary['total_revenue'] ?? 0) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-8 text-center text-gray-400 text-xs">
            <p>Sistem Monitoring & Rekapitulasi Tiket Wisata - &copy; 2024</p>
        </div>

    </main>

    <script>
        // Data from Controller
        const labels = <?= json_encode($labels) ?>;
        const revenueData = <?= json_encode($revenueData) ?>;
        
        const typeLabels = <?= json_encode(array_column($salesByType, 'type')) ?>;
        const typeData = <?= json_encode(array_column($salesByType, 'count')) ?>;

        // 1. Line Chart (Revenue)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueData,
                    borderColor: '#0d9488', // teal-600
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                        }
                    }
                }
            }
        });

        // 2. Pie Chart (Types)
        const ctxType = document.getElementById('typeChart').getContext('2d');
        new Chart(ctxType, {
            type: 'doughnut',
            data: {
                labels: typeLabels,
                datasets: [{
                    data: typeData,
                    backgroundColor: [
                        '#3b82f6', // blue
                        '#eab308', // yellow
                        '#ec4899', // pink
                        '#8b5cf6'  // purple
                    ]
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
</body>
</html>
