<?php
/**
 * Master Database Setup & Seeder Runner (Idempotent)
 * Hollynice Ticket System - Banjarnegara
 */
require_once __DIR__ . '/config/db.php';

echo "=== Hollynice Ticket System: Unified Database Setup ===" . PHP_EOL;

try {
    // 1. Users Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'operator', 'customer', 'monitor') DEFAULT 'customer',
        full_name VARCHAR(100),
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "[✓] Table 'users' verified." . PHP_EOL;

    // 2. Products Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name_en VARCHAR(100) NOT NULL,
        name_id VARCHAR(100) NOT NULL,
        description_en TEXT,
        description_id TEXT,
        price DECIMAL(10, 2) NOT NULL,
        type ENUM('tour', 'entrance', 'event') NOT NULL,
        image VARCHAR(255),
        youtube_url VARCHAR(255) DEFAULT NULL,
        duration VARCHAR(100),
        itinerary_en TEXT,
        itinerary_id TEXT,
        facilities_en TEXT,
        facilities_id TEXT,
        policy_en TEXT,
        policy_id TEXT,
        rating DECIMAL(3, 1) DEFAULT 5.0,
        review_count INT DEFAULT 0,
        latitude DECIMAL(10, 8),
        longitude DECIMAL(11, 8),
        tagline_en VARCHAR(255),
        tagline_id VARCHAR(255),
        event_date DATETIME,
        event_end_date DATETIME,
        meeting_point_en TEXT,
        meeting_point_id TEXT,
        accessibility_en TEXT,
        accessibility_id TEXT,
        what_to_bring_en TEXT,
        what_to_bring_id TEXT,
        quota INT DEFAULT 100,
        price_variants JSON,
        opening_hours_en TEXT,
        opening_hours_id TEXT,
        address_en TEXT,
        address_id TEXT,
        exclusions_en TEXT,
        exclusions_id TEXT,
        terms_en TEXT,
        terms_id TEXT,
        contact_phone VARCHAR(20),
        contact_email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "[✓] Table 'products' verified." . PHP_EOL;

    // 3. Product Images Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");
    echo "[✓] Table 'product_images' verified." . PHP_EOL;

    // 4. Orders Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
        payment_method VARCHAR(50),
        transaction_id VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");
    echo "[✓] Table 'orders' verified." . PHP_EOL;

    // 5. Order Items Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        visit_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");
    echo "[✓] Table 'order_items' verified." . PHP_EOL;

    // 6. Tickets Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS tickets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_item_id INT NOT NULL,
        ticket_code VARCHAR(50) NOT NULL UNIQUE,
        status ENUM('valid', 'used', 'expired', 'pending_payment') DEFAULT 'pending_payment',
        used_at DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE
    )");
    // Ensure ENUM column includes pending_payment for existing installations
    $pdo->exec("ALTER TABLE tickets MODIFY COLUMN status ENUM('valid', 'used', 'expired', 'pending_payment') DEFAULT 'pending_payment'");
    echo "[✓] Table 'tickets' verified." . PHP_EOL;

    // 7. Accommodations Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS accommodations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type ENUM('hotel', 'homestay', 'villa') NOT NULL,
        description TEXT,
        price_per_night DECIMAL(10, 2) NOT NULL,
        location VARCHAR(255),
        image VARCHAR(255),
        rating DECIMAL(2, 1) DEFAULT 0,
        facilities TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "[✓] Table 'accommodations' verified." . PHP_EOL;

    // 8. Culinary Spots Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS culinary_spots (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        location VARCHAR(255),
        price_range VARCHAR(100),
        opening_hours VARCHAR(100),
        signature_dish VARCHAR(255),
        rating DECIMAL(2, 1) DEFAULT 0,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "[✓] Table 'culinary_spots' verified." . PHP_EOL;

    // 9. Souvenirs Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS souvenirs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "[✓] Table 'souvenirs' verified." . PHP_EOL;

    // 10. Transportations Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS transportations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type ENUM('rental', 'travel', 'pickup') NOT NULL,
        description TEXT,
        facilities TEXT,
        capacity INT DEFAULT 4,
        price_range VARCHAR(100),
        contact_number VARCHAR(50),
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "[✓] Table 'transportations' verified." . PHP_EOL;

    // Seed default users if table is empty
    $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        $users = [
            ['admin', password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'Super Administrator', 'admin@example.com'],
            ['operator', password_hash('operator123', PASSWORD_DEFAULT), 'operator', 'Loket Operator', 'operator@example.com'],
            ['user', password_hash('user123', PASSWORD_DEFAULT), 'customer', 'Pelancong Wisata', 'user@example.com'],
            ['dinas', password_hash('password', PASSWORD_DEFAULT), 'monitor', 'Dinas Pariwisata & DPPKAD', 'dinas@example.com']
        ];
        $stmtUser = $pdo->prepare("INSERT INTO users (username, password, role, full_name, email) VALUES (?, ?, ?, ?, ?)");
        foreach ($users as $u) {
            $stmtUser->execute($u);
        }
        echo "[✓] Default users seeded (admin, operator, user, dinas)." . PHP_EOL;
    }

    echo PHP_EOL . "=== Database Setup Completed Successfully! ===" . PHP_EOL;

} catch (PDOException $e) {
    die("Database Setup Failed: " . $e->getMessage() . PHP_EOL);
}

