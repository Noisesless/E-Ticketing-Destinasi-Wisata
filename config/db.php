<?php
// config/db.php
if (!function_exists('env')) {
    require_once __DIR__ . '/../includes/functions.php';
}

// KONFIGURASI DATABASE DENGAN .ENV (Vibes Coding Standard)
$host = env('DB_HOST', 'localhost');
$port = env('DB_PORT', '3306');
$db   = env('DB_NAME', 'hollynice_ticket_db');
$user = env('DB_USER', 'root');
$pass = env('DB_PASS', '');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Coba koneksi langsung ke database (Standard untuk Hosting)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Jika gagal koneksi...
    
    // Cek jika errornya adalah database tidak ditemukan (Kode 1049) DAN kita sedang di localhost (Local Dev)
    // Maka coba buat databasenya otomatis.
    if ($e->getCode() == 1049 && ($host == 'localhost' || $host == '127.0.0.1')) {
        try {
            // Koneksi tanpa nama database
            $pdo = new PDO("mysql:host=$host;charset=$charset", $user, $pass, $options);
            // Buat database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`");
            // Gunakan database
            $pdo->exec("USE `$db`");
        } catch (\PDOException $ex) {
            // Jika masih gagal juga, tampilkan error
             die("Koneksi Database Gagal: " . $ex->getMessage());
        }
    } else {
        // Jika error lain (misal password salah), tampilkan error
        die("Koneksi Database Gagal: " . $e->getMessage() . ". <br>Pastikan Anda sudah mengedit file <b>config/db.php</b> dengan username/password database hosting yang benar.");
    }
}
