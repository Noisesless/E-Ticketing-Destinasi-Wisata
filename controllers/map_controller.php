<?php
require_once 'config/db.php';

// Fetch Accommodations
$stmt = $pdo->query("SELECT * FROM accommodations");
$accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Culinary
$stmt = $pdo->query("SELECT * FROM culinary_spots");
$culinary = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dummy Tourist Destinations
$destinations = [
    [
        'slug' => 'kawah-sikidang',
        'name' => 'Kawah Sikidang',
        'type' => 'Wisata Alam',
        'description' => 'Kawah vulkanik aktif yang populer dengan pemandangan asap belerang.',
        'image' => 'img/produk/lukisan1.jpeg', 
        'lat' => -7.215,
        'lon' => 109.907
    ],
    [
        'slug' => 'candi-arjuna',
        'name' => 'Candi Arjuna',
        'type' => 'Wisata Sejarah',
        'description' => 'Kompleks candi Hindu tertua di Jawa yang terletak di dataran tinggi Dieng.',
        'image' => 'img/produk/candi.jpeg',
        'lat' => -7.205,
        'lon' => 109.909
    ],
    [
        'slug' => 'telaga-warna',
        'name' => 'Telaga Warna',
        'type' => 'Wisata Alam',
        'description' => 'Danau dengan warna air yang bisa berubah-ubah karena kandungan belerang.',
        'image' => 'img/produk/lukisan2.jpeg',
        'lat' => -7.213,
        'lon' => 109.914
    ],
    [
        'slug' => 'arung-jeram-serayu',
        'name' => 'Arung Jeram Serayu',
        'type' => 'Wisata Petualangan',
        'description' => 'Pengalaman rafting seru di sungai Serayu dengan grade tantangan yang bervariasi.',
        'image' => 'img/produk/lukisan3.jpeg',
        'lat' => -7.385,
        'lon' => 109.702
    ],
    [
        'slug' => 'curug-pitu',
        'name' => 'Curug Pitu',
        'type' => 'Wisata Alam',
        'description' => 'Air terjun bertingkat tujuh yang menawarkan kesegaran alami.',
        'image' => 'img/produk/lukisan4.jpeg',
        'lat' => -7.350,
        'lon' => 109.750
    ]
];

// Helper to add random coords to DB items (since they don't have lat/lon columns)
function addRandomCoords(&$items, $baseLat, $baseLon) {
    foreach ($items as &$item) {
        // Random spread around Banjarnegara
        $item['lat'] = $baseLat + (mt_rand(-80, 80) / 1000); 
        $item['lon'] = $baseLon + (mt_rand(-80, 80) / 1000);
        
        // Ensure image path is correct for view
        if (isset($item['image']) && !empty($item['image'])) {
             if (!str_starts_with($item['image'], 'img/') && !str_starts_with($item['image'], 'http')) {
                 $item['image'] = 'img/' . $item['image'];
             }
        } else {
            $item['image'] = 'img/logo.jpg'; // fallback
        }
    }
}

// Banjarnegara center roughly -7.39, 109.69
addRandomCoords($accommodations, -7.39, 109.69);
addRandomCoords($culinary, -7.39, 109.69);

// Combine all for JS
$mapData = [];

foreach ($destinations as $d) {
    $mapData[] = [
        'name' => $d['name'],
        'type' => $d['type'],
        'category' => 'tour',
        'description' => $d['description'],
        'image' => $d['image'],
        'lat' => $d['lat'],
        'lon' => $d['lon'],
        'url' => 'index.php?page=destination&id=' . $d['slug']
    ];
}

foreach ($accommodations as $a) {
    $mapData[] = [
        'name' => $a['name'],
        'type' => ucfirst($a['type']),
        'category' => 'accommodation',
        'description' => $a['description'],
        'image' => $a['image'],
        'lat' => $a['lat'],
        'lon' => $a['lon'],
        'url' => 'index.php?page=accommodation_detail&id=' . $a['id']
    ];
}

foreach ($culinary as $c) {
    $mapData[] = [
        'name' => $c['name'],
        'type' => 'Kuliner',
        'category' => 'culinary',
        'description' => $c['description'],
        'image' => $c['image'],
        'lat' => $c['lat'],
        'lon' => $c['lon'],
        'url' => 'index.php?page=culinary&id=' . $c['id']
    ];
}

// Pass data to view
require 'views/desktop/map.php';
