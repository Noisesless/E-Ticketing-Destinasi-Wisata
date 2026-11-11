<?php
require 'config/db.php';

echo "<h1>Database Setup</h1>";
echo "<p>Connected to database: <strong>" . $db . "</strong></p>";

try {
    // 1. Table: Accommodations
    $sql = "CREATE TABLE IF NOT EXISTS accommodations (
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
    )";
    $pdo->exec($sql);
    echo "Table 'accommodations' checked/created.<br>";

    // 2. Table: Culinary Spots
    $sql = "CREATE TABLE IF NOT EXISTS culinary_spots (
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
    )";
    $pdo->exec($sql);
    echo "Table 'culinary_spots' checked/created.<br>";

    // 3. Table: Souvenirs
    $sql = "CREATE TABLE IF NOT EXISTS souvenirs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'souvenirs' checked/created.<br>";

    // --- SEED DATA ---

    // Seed Accommodations
    $stmt = $pdo->query("SELECT COUNT(*) FROM accommodations");
    if ($stmt->fetchColumn() == 0) {
        $data = [
            [
                'name' => 'Grand Jan Mountain View',
                'type' => 'hotel',
                'description' => 'Hotel bintang 4 dengan pemandangan gunung yang menakjubkan.',
                'price_per_night' => 750000,
                'location' => 'Jl. Raya Puncak No. 10',
                'image' => 'hotel_1.jpg',
                'rating' => 4.8,
                'facilities' => 'WiFi, Kolam Renang, Restoran, AC, Parkir'
            ],
            [
                'name' => 'Homestay Bu Siti',
                'type' => 'homestay',
                'description' => 'Penginapan nyaman serasa di rumah sendiri.',
                'price_per_night' => 150000,
                'location' => 'Desa Wisata Jan, Gang 3',
                'image' => 'homestay_1.jpg',
                'rating' => 4.5,
                'facilities' => 'WiFi, Sarapan, Dapur Bersama, Kipas Angin'
            ],
            [
                'name' => 'Villa Cendana',
                'type' => 'villa',
                'description' => 'Villa privat dengan 3 kamar tidur, cocok untuk rombongan.',
                'price_per_night' => 1200000,
                'location' => 'Bukit Jan Indah',
                'image' => 'villa_1.jpg',
                'rating' => 4.9,
                'facilities' => 'WiFi, Dapur Lengkap, BBQ Area, 3 Kamar, TV Kabel'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO accommodations (name, type, description, price_per_night, location, image, rating, facilities) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($data as $item) {
            $stmt->execute([$item['name'], $item['type'], $item['description'], $item['price_per_night'], $item['location'], $item['image'], $item['rating'], $item['facilities']]);
        }
        echo "Seeded 'accommodations'.<br>";
    }

    // Seed Culinary
    $stmt = $pdo->query("SELECT COUNT(*) FROM culinary_spots");
    if ($stmt->fetchColumn() == 0) {
        $data = [
            [
                'name' => 'Warung Nasi Liwet Bu Sri',
                'description' => 'Nasi liwet khas dengan lauk ayam kampung dan sambal yang mantap.',
                'location' => 'Jl. Kuliner No. 5',
                'price_range' => 'Rp 20.000 - Rp 50.000',
                'opening_hours' => '10.00 - 22.00',
                'signature_dish' => 'Nasi Liwet Komplit',
                'rating' => 4.7,
                'image' => 'kuliner_1.jpg'
            ],
            [
                'name' => 'Kopi Janji Jiwa',
                'description' => 'Tempat nongkrong asik dengan berbagai pilihan kopi kekinian.',
                'location' => 'Ruko Jan Square',
                'price_range' => 'Rp 15.000 - Rp 35.000',
                'opening_hours' => '08.00 - 23.00',
                'signature_dish' => 'Es Kopi Susu',
                'rating' => 4.5,
                'image' => 'kuliner_2.jpg'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO culinary_spots (name, description, location, price_range, opening_hours, signature_dish, rating, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($data as $item) {
            $stmt->execute([$item['name'], $item['description'], $item['location'], $item['price_range'], $item['opening_hours'], $item['signature_dish'], $item['rating'], $item['image']]);
        }
        echo "Seeded 'culinary_spots'.<br>";
    }

    // Seed Souvenirs
    $stmt = $pdo->query("SELECT COUNT(*) FROM souvenirs");
    if ($stmt->fetchColumn() == 0) {
        $data = [
            [
                'name' => 'Kaos Khas Jan',
                'description' => 'Kaos katun dengan desain unik khas daerah Jan.',
                'price' => 75000,
                'image' => 'souvenir_1.jpg'
            ],
            [
                'name' => 'Gantungan Kunci',
                'description' => 'Gantungan kunci kayu buatan tangan.',
                'price' => 10000,
                'image' => 'souvenir_2.jpg'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO souvenirs (name, description, price, image) VALUES (?, ?, ?, ?)");
        foreach ($data as $item) {
            $stmt->execute([$item['name'], $item['description'], $item['price'], $item['image']]);
        }
        echo "Seeded 'souvenirs'.<br>";
    }

    echo "<h3>Setup Completed Successfully!</h3>";
    echo "<a href='index.php'>Go to Home</a>";

} catch (PDOException $e) {
    echo "<h3>Error:</h3> " . $e->getMessage();
}
