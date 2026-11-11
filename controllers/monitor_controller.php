<?php

// 1. Total Revenue & Tickets (Valid/Paid Only)
$stmt = $pdo->query("
    SELECT 
        SUM(total_amount) as total_revenue,
        COUNT(id) as total_transactions
    FROM orders 
    WHERE status = 'paid'
");
$summary = $stmt->fetch();

$stmtTickets = $pdo->query("
    SELECT COUNT(id) as total_tickets
    FROM tickets
    WHERE status IN ('valid', 'used')
");
$ticketSummary = $stmtTickets->fetch();

// 2. Sales by Ticket Type (For Pie Chart)
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
$salesByType = $stmtType->fetchAll();

// 3. Daily Sales - Last 30 Days (For Line Chart)
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
$dailySales = $stmtDaily->fetchAll();

// Prepare Data for Charts
$labels = [];
$revenueData = [];
$transactionData = [];

foreach ($dailySales as $day) {
    $labels[] = date('d M', strtotime($day['date']));
    $revenueData[] = $day['revenue'];
    $transactionData[] = $day['transactions'];
}

// Pass data to view
require 'views/monitor/dashboard.php';
