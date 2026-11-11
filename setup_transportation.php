<?php
require 'config/db.php';

echo "<h1>Transportation Setup</h1>";

try {
    // 1. Table: Transportations
    $sql = "CREATE TABLE IF NOT EXISTS transportations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        type ENUM('rental', 'travel', 'pickup') NOT NULL,
        description TEXT,
        price_range VARCHAR(100),
        contact_number VARCHAR(50),
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'transportations' checked/created.<br>";

    // 2. Seed Data
    $stmt = $pdo->query("SELECT COUNT(*) FROM transportations");
    if ($stmt->fetchColumn() == 0) {
        $data = [
            [
                'name' => 'Dieng Travel Executive',
                'type' => 'travel',
                'description' => 'Layanan travel premium door-to-door rute Banjarnegara - Wonosobo - Yogyakarta - Semarang. Armada HiAce terbaru dengan fasilitas lengkap.',
                'price_range' => 'Rp 100.000 - Rp 150.000 / orang',
                'contact_number' => '0812-3456-7890',
                'image' => 'travel_1.jpg'
            ],
            [
                'name' => 'Rental Mobil "Mas Banjar"',
                'type' => 'rental',
                'description' => 'Sewa mobil lepas kunci atau dengan supir. Tersedia Avanza, Innova, hingga Alphard. Kondisi mobil prima dan bersih.',
                'price_range' => 'Mulai Rp 300.000 / 24 jam',
                'contact_number' => '0857-1234-5678',
                'image' => 'rental_1.jpg'
            ],
            [
                'name' => 'Ojek Wisata Kawah',
                'type' => 'pickup',
                'description' => 'Jasa antar jemput khusus area wisata Dieng (Kawah Sikidang, Candi Arjuna). Driver ramah dan hafal jalan tikus untuk menghindari macet.',
                'price_range' => 'Rp 15.000 - Rp 50.000 / trip',
                'contact_number' => '0899-8877-6655',
                'image' => 'ojek_1.jpg'
            ],
            [
                'name' => 'Bus Pariwisata "Serayu Trans"',
                'type' => 'rental',
                'description' => 'Sewa bus pariwisata medium dan big bus untuk rombongan besar. Fasilitas karaoke, AC dingin, dan bagasi luas.',
                'price_range' => 'Hubungi CS untuk penawaran',
                'contact_number' => '0286-555-444',
                'image' => 'bus_1.jpg'
            ]
        ];

        $stmt = $pdo->prepare("INSERT INTO transportations (name, type, description, price_range, contact_number, image) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($data as $item) {
            $stmt->execute([$item['name'], $item['type'], $item['description'], $item['price_range'], $item['contact_number'], $item['image']]);
        }
        echo "Seeded 'transportations'.<br>";
    } else {
        echo "Table 'transportations' already has data.<br>";
    }

    echo "<h3>Setup Completed Successfully!</h3>";
    echo "<a href='index.php?page=transportation'>Go to Transportation Page</a>";

} catch (PDOException $e) {
    echo "<h3>Error:</h3> " . $e->getMessage();
}
