<?php

function getMonitorData(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT 
            SUM(total_amount) as total_revenue,
            COUNT(id) as total_transactions
        FROM orders 
        WHERE status = 'paid'
    ");
    $summary = $stmt->fetch(PDO::FETCH_ASSOC) ?: [
        'total_revenue' => 0,
        'total_transactions' => 0,
    ];

    $stmtTickets = $pdo->query("
        SELECT COUNT(id) as total_tickets
        FROM tickets
        WHERE status IN ('valid', 'used')
    ");
    $ticketSummary = $stmtTickets->fetch(PDO::FETCH_ASSOC) ?: [
        'total_tickets' => 0,
    ];

    $stmtType = $pdo->query("
        SELECT 
            p.type,
            COUNT(oi.id) as count,
            SUM(oi.price * oi.quantity) as revenue
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE o.status = 'paid'
        GROUP BY p.type
    ");
    $salesByType = $stmtType->fetchAll(PDO::FETCH_ASSOC);

    $stmtDaily = $pdo->query("
        SELECT 
            DATE(created_at) as date,
            SUM(total_amount) as revenue,
            COUNT(id) as transactions
        FROM orders 
        WHERE status = 'paid' 
          AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
    $dailySales = $stmtDaily->fetchAll(PDO::FETCH_ASSOC);

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

    $stmtPos = $pdo->query("
        SELECT 
            pos_code,
            payment_category,
            SUM(amount) AS total_amount,
            COUNT(id) AS total_entries
        FROM pos_transaction_logs
        WHERE DATE(created_at) = CURDATE()
        GROUP BY pos_code, payment_category
        ORDER BY pos_code, payment_category
    ");
    $posSettlementsToday = $stmtPos->fetchAll(PDO::FETCH_ASSOC);

    $labels = [];
    $revenueData = [];
    $transactionData = [];

    foreach ($dailySales as $day) {
        $labels[] = date('d M', strtotime($day['date']));
        $revenueData[] = $day['revenue'];
        $transactionData[] = $day['transactions'];
    }

    return [
        'summary' => $summary,
        'ticketSummary' => $ticketSummary,
        'salesByType' => $salesByType,
        'labels' => $labels,
        'revenueData' => $revenueData,
        'transactionData' => $transactionData,
        'posSettlementsToday' => $posSettlementsToday,
    ];
}

function buildDummyTransactions(string $mode, string $dateString): array {
    $base = strtotime($dateString) ?: time();
    $rows = [];

    if ($mode === 'daily') {
        for ($i = 1; $i <= 10; $i++) {
            $rows[] = [
                'date' => date('d/m/Y', $base),
                'ref' => sprintf('TRX-HR-%04d', $i),
                'product' => $i % 3 === 0 ? 'Tiket Paket Wisata' : ($i % 2 === 0 ? 'Tiket Masuk Obyek Wisata' : 'Tiket Acara'),
                'qty' => rand(1, 8),
                'amount' => rand(50000, 350000),
            ];
        }
    } elseif ($mode === 'monthly') {
        $start = strtotime(date('Y-m-01', $base));
        for ($i = 0; $i < 10; $i++) {
            $dayTime = strtotime("+$i day", $start);
            $rows[] = [
                'date' => date('d/m/Y', $dayTime),
                'ref' => sprintf('TRX-BL-%04d', $i + 1),
                'product' => $i % 3 === 0 ? 'Tiket Terusan' : ($i % 2 === 0 ? 'Tiket Wisata Alam' : 'Tiket Event Khusus'),
                'qty' => rand(2, 15),
                'amount' => rand(150000, 750000),
            ];
        }
    } else {
        for ($m = 1; $m <= 12; $m++) {
            $monthTime = strtotime(date('Y', $base) . '-' . str_pad((string)$m, 2, '0', STR_PAD_LEFT) . '-01');
            $rows[] = [
                'date' => date('M Y', $monthTime),
                'ref' => sprintf('TRX-TH-%02d', $m),
                'product' => 'Rekap Bulanan Semua Tiket',
                'qty' => rand(50, 250),
                'amount' => rand(5000000, 25000000),
            ];
        }
    }

    return $rows;
}

$export = $_GET['export'] ?? null;

if ($export === 'pdf') {
    $mode = $_GET['mode'] ?? 'daily';
    if (!in_array($mode, ['daily', 'monthly', 'yearly'], true)) {
        $mode = 'daily';
    }
    $dateInput = $_GET['date'] ?? date('Y-m-d');
    if (strtotime($dateInput) === false) {
        $dateInput = date('Y-m-d');
    }

    $data = getMonitorData($pdo);
    $dummyTransactions = buildDummyTransactions($mode, $dateInput);

    if ($mode === 'daily') {
        $periodLabel = 'Harian - ' . date('d M Y', strtotime($dateInput));
    } elseif ($mode === 'monthly') {
        $periodLabel = 'Bulanan - ' . date('F Y', strtotime($dateInput));
    } else {
        $periodLabel = 'Tahunan - ' . date('Y', strtotime($dateInput));
    }

    require_once __DIR__ . '/../TCPDF/tcpdf.php';

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Hollynice Ticket System');
    $pdf->SetAuthor('BPPKAD Kabupaten Wonosobo');
    $pdf->SetTitle('Laporan Monitoring PAD E-Ticketing');
    $pdf->SetMargins(15, 20, 15);
    $pdf->SetAutoPageBreak(true, 20);
    $pdf->AddPage();

    $summary = $data['summary'];
    $ticketSummary = $data['ticketSummary'];
    $salesByType = $data['salesByType'];

    $totalRevenue = $summary['total_revenue'] ?? 0;
    $totalTransactions = $summary['total_transactions'] ?? 0;
    $totalTickets = $ticketSummary['total_tickets'] ?? 0;

    $html = '<h3 style="text-align:center;margin:0;font-size:12px;">PEMERINTAH KABUPATEN WONOSOBO</h3>';
    $html .= '<h3 style="text-align:center;margin:2px 0 10px;font-size:11px;">BPPKAD & DINAS PARIWISATA</h3>';
    $html .= '<h2 style="text-align:center;margin:0 0 4px;font-size:13px;">LAPORAN MONITORING PAD E-TICKETING WISATA</h2>';
    $html .= '<p style="text-align:center;font-size:10px;margin:0 0 10px;">Periode: ' . htmlspecialchars($periodLabel, ENT_QUOTES, 'UTF-8') . '</p>';
    $html .= '<hr style="margin:0 0 15px 0;">';

    $html .= '<table cellpadding="4" cellspacing="0" border="1" style="font-size:10px;margin-bottom:15px;">';
    $html .= '<tr style="background-color:#f1f5f9;font-weight:bold;">';
    $html .= '<td>Total Pendapatan Terverifikasi</td>';
    $html .= '<td>Total Tiket Terjual</td>';
    $html .= '<td>Total Transaksi</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td>Rp ' . number_format((float)$totalRevenue, 0, ',', '.') . '</td>';
    $html .= '<td>' . number_format((float)$totalTickets, 0, ',', '.') . ' tiket</td>';
    $html .= '<td>' . number_format((float)$totalTransactions, 0, ',', '.') . ' transaksi</td>';
    $html .= '</tr>';
    $html .= '</table>';

    if (!empty($salesByType)) {
        $html .= '<h4 style="font-size:11px;margin:15px 0 5px 0;">Ringkasan per Kategori Tiket</h4>';
        $html .= '<table cellpadding="4" cellspacing="0" border="1" style="font-size:9px;margin-bottom:15px;">';
        $html .= '<tr style="background-color:#f8fafc;font-weight:bold;">';
        $html .= '<td>Kategori Tiket</td>';
        $html .= '<td>Jumlah Item</td>';
        $html .= '<td>Estimasi Pendapatan</td>';
        $html .= '</tr>';
        foreach ($salesByType as $row) {
            $label = match ($row['type']) {
                'tour' => 'Paket Wisata',
                'event' => 'Acara / Event',
                'entrance' => 'Tiket Masuk Destinasi',
                default => ucfirst((string)$row['type']),
            };
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td>' . number_format((float)$row['count'], 0, ',', '.') . ' item</td>';
            $html .= '<td>Rp ' . number_format((float)$row['revenue'], 0, ',', '.') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
    }

    $html .= '<h4 style="font-size:11px;margin:15px 0 5px 0;">Rincian Transaksi (' . htmlspecialchars($periodLabel, ENT_QUOTES, 'UTF-8') . ')</h4>';
    $html .= '<table cellpadding="3" cellspacing="0" border="1" style="font-size:8px;">';
    $html .= '<tr style="background-color:#f8fafc;font-weight:bold;">';
    $html .= '<td width="18%">Tanggal</td>';
    $html .= '<td width="22%">No. Referensi</td>';
    $html .= '<td width="32%">Deskripsi Transaksi</td>';
    $html .= '<td width="10%">Qty</td>';
    $html .= '<td width="18%">Nominal (Rp)</td>';
    $html .= '</tr>';

    $sumAmount = 0;
    foreach ($dummyTransactions as $trx) {
        $sumAmount += $trx['amount'];
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($trx['date'], ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td>' . htmlspecialchars($trx['ref'], ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td>' . htmlspecialchars($trx['product'], ENT_QUOTES, 'UTF-8') . '</td>';
        $html .= '<td align="center">' . (int)$trx['qty'] . '</td>';
        $html .= '<td align="right">' . number_format((float)$trx['amount'], 0, ',', '.') . '</td>';
        $html .= '</tr>';
    }

    $html .= '<tr style="font-weight:bold;background-color:#f1f5f9;">';
    $html .= '<td colspan="4" align="right">Total Subtotal Transaksi</td>';
    $html .= '<td align="right">Rp ' . number_format((float)$sumAmount, 0, ',', '.') . '</td>';
    $html .= '</tr>';
    $html .= '</table>';

    $html .= '<p style="font-size:8px;color:#64748b;margin-top:15px;">Dokumen ini di-generate secara otomatis melalui Modul Monitoring BPPKAD - Sistem E-Ticketing Geopark Wonosobo pada ' . date('d/m/Y H:i:s') . ' WIB.</p>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $filename = 'Laporan_PAD_ETicketing_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $periodLabel) . '.pdf';
    $pdf->Output($filename, 'I');
    exit;
}

$data = getMonitorData($pdo);

$summary = $data['summary'];
$ticketSummary = $data['ticketSummary'];
$salesByType = $data['salesByType'];
$labels = $data['labels'];
$revenueData = $data['revenueData'];
$transactionData = $data['transactionData'];
$posSettlementsToday = $data['posSettlementsToday'];

require 'views/monitor/dashboard.php';
