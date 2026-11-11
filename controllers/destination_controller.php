<?php
// Dummy Tourist Destinations Data (Shared with map_controller logic conceptually)
$destinations = [
    'kawah-sikidang' => [
        'name' => 'Kawah Sikidang',
        'type' => 'Wisata Alam',
        'description' => 'Kawah vulkanik aktif yang populer dengan pemandangan asap belerang. Pengunjung dapat melihat aktivitas vulkanik dari jarak dekat dengan aman.',
        'image' => 'img/produk/lukisan1.jpeg', 
        'location' => 'Dieng Kulon, Batur, Banjarnegara',
        'ticket_price' => 'Rp 20.000',
        'opening_hours' => '07.00 - 17.00 WIB'
    ],
    'candi-arjuna' => [
        'name' => 'Candi Arjuna',
        'type' => 'Wisata Sejarah',
        'description' => 'Kompleks candi Hindu tertua di Jawa yang terletak di dataran tinggi Dieng. Menjadi lokasi utama pelaksanaan Dieng Culture Festival.',
        'image' => 'img/produk/candi.jpeg',
        'location' => 'Dieng Kulon, Batur, Banjarnegara',
        'ticket_price' => 'Rp 20.000',
        'opening_hours' => '07.00 - 17.00 WIB'
    ],
    'telaga-warna' => [
        'name' => 'Telaga Warna',
        'type' => 'Wisata Alam',
        'description' => 'Danau dengan warna air yang bisa berubah-ubah karena kandungan belerang. Fenomena alam yang memukau dikelilingi hutan rimbun.',
        'image' => 'img/produk/lukisan2.jpeg',
        'location' => 'Dieng Wetan, Kejajar, Wonosobo/Banjarnegara',
        'ticket_price' => 'Rp 25.000',
        'opening_hours' => '06.00 - 18.00 WIB'
    ],
    'arung-jeram-serayu' => [
        'name' => 'Arung Jeram Serayu',
        'type' => 'Wisata Petualangan',
        'description' => 'Pengalaman rafting seru di sungai Serayu dengan grade tantangan yang bervariasi. Cocok untuk pemula hingga profesional.',
        'image' => 'img/produk/lukisan3.jpeg',
        'location' => 'Sungai Serayu, Banjarnegara',
        'ticket_price' => 'Mulai Rp 185.000',
        'opening_hours' => '08.00 - 16.00 WIB'
    ],
    'curug-pitu' => [
        'name' => 'Curug Pitu',
        'type' => 'Wisata Alam',
        'description' => 'Air terjun bertingkat tujuh yang menawarkan kesegaran alami. Lokasi yang tenang dan asri untuk menenangkan diri.',
        'image' => 'img/produk/lukisan4.jpeg',
        'location' => 'Sigaluh, Banjarnegara',
        'ticket_price' => 'Rp 5.000',
        'opening_hours' => '08.00 - 17.00 WIB'
    ]
];

$id = $_GET['id'] ?? null;

if ($id && isset($destinations[$id])) {
    $destination = $destinations[$id];
    require 'views/desktop/destination_detail.php';
} else {
    // If no valid ID, redirect to map
    header("Location: index.php?page=map");
    exit;
}
