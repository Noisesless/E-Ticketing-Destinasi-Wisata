<?php
require 'config/db.php';

try {
    // 1. Create Table
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
    echo "Table 'accommodations' created successfully.<br>";

    // 2. Check if data exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM accommodations");
    if ($stmt->fetchColumn() > 0) {
        echo "Data already exists. Skipping seed.<br>";
    } else {
        // 3. Seed Data
        $data = [
            [
                'name' => 'Grand Jan Mountain View',
                'type' => 'hotel',
                'description' => 'Hotel bintang 4 dengan pemandangan gunung yang menakjubkan. Fasilitas lengkap termasuk kolam renang air hangat dan sarapan buffet.',
                'price_per_night' => 750000,
                'location' => 'Jl. Raya Puncak No. 10',
                'image' => 'hotel_1.jpg',
                'rating' => 4.8,
                'facilities' => 'WiFi, Kolam Renang, Restoran, AC, Parkir'
            ],
            [
                'name' => 'Homestay Bu Siti',
                'type' => 'homestay',
                'description' => 'Penginapan nyaman serasa di rumah sendiri. Cocok untuk backpacker dan keluarga kecil. Termasuk sarapan nasi goreng spesial.',
                'price_per_night' => 150000,
                'location' => 'Desa Wisata Jan, Gang 3',
                'image' => 'homestay_1.jpg',
                'rating' => 4.5,
                'facilities' => 'WiFi, Sarapan, Dapur Bersama, Kipas Angin'
            ],
            [
                'name' => 'Villa Cendana',
                'type' => 'villa',
                'description' => 'Villa privat dengan 3 kamar tidur, cocok untuk rombongan. Halaman luas untuk BBQ dan acara keluarga.',
                'price_per_night' => 1200000,
                'location' => 'Bukit Jan Indah',
                'image' => 'villa_1.jpg',
                'rating' => 4.9,
                'facilities' => 'WiFi, Dapur Lengkap, BBQ Area, 3 Kamar, TV Kabel'
            ],
            [
                'name' => 'Losmen Asri',
                'type' => 'homestay',
                'description' => 'Penginapan hemat dengan suasana asri pedesaan. Dekat dengan lokasi wisata utama.',
                'price_per_night' => 100000,
                'location' => 'Jl. Alternatif Km 5',
                'image' => 'homestay_2.jpg',
                'rating' => 4.2,
                'facilities' => 'Parkir Motor, Kamar Mandi Luar, Warung 24 Jam'
            ],
            [
                'name' => 'Hotel Melati Putih',
                'type' => 'hotel',
                'description' => 'Hotel budget yang bersih dan strategis. Akses mudah ke transportasi umum.',
                'price_per_night' => 300000,
                'location' => 'Pusat Kota Jan',
                'image' => 'hotel_2.jpg',
                'rating' => 4.0,
                'facilities' => 'WiFi, AC, TV, Resepsionis 24 Jam'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO accommodations (name, type, description, price_per_night, location, image, rating, facilities) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($data as $item) {
            $stmt->execute([
                $item['name'], 
                $item['type'], 
                $item['description'], 
                $item['price_per_night'], 
                $item['location'], 
                $item['image'], 
                $item['rating'], 
                $item['facilities']
            ]);
        }
        echo "Dummy data seeded successfully.<br>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
