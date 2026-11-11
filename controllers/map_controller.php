<?php
require_once 'config/db.php';

$stmt = $pdo->query("SELECT * FROM accommodations");
$accommodations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM culinary_spots");
$culinary = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM transportations");
$transportations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tourist Destinations (Geosite Wonosobo)
$destinations = [
    [
        'slug' => 'kawah-sikidang',
        'name' => 'Kawah Sikidang',
        'type' => 'Geosite Vulkanik',
        'description' => 'Kawah vulkanik aktif di Dataran Tinggi Dieng dengan lumpur mendidih dan uap belerang.',
        'image' => 'img/overlooking-jurrasic-park-QFRXFY7.jpg',
        'lat' => -7.215,
        'lon' => 109.907
    ],
    [
        'slug' => 'kompleks-curug-merawu',
        'name' => 'Kompleks Curug Merawu',
        'type' => 'Geosite Air Terjun',
        'description' => 'Kompleks air terjun dengan tebing hijau dan udara pegunungan sejuk di koridor Geopark Dieng.',
        'image' => 'img/autumn-waterfall-PJH8WT9.jpg',
        'lat' => -7.230,
        'lon' => 109.900
    ],
    [
        'slug' => 'kawah-timbang',
        'name' => 'Kawah Timbang',
        'type' => 'Geosite Vulkanik',
        'description' => 'Manifestasi geotermal penting di lereng Dieng yang merekam aktivitas vulkanik.',
        'image' => 'img/hiking-EGYTFBZ.jpg',
        'lat' => -7.238,
        'lon' => 109.892
    ],
    [
        'slug' => 'sumur-jalatunda',
        'name' => 'Sumur Jalatunda',
        'type' => 'Geosite Vulkanik',
        'description' => 'Sumur vulkanik raksasa berair kehijauan yang dikelilingi tebing batuan andesit.',
        'image' => 'img/long-bun-XPF9W4A3lvM-unsplash.jpg',
        'lat' => -7.228,
        'lon' => 109.875
    ],
    [
        'slug' => 'kawah-sinila',
        'name' => 'Kawah Sinila',
        'type' => 'Geosite Vulkanik',
        'description' => 'Kawah bersejarah terkait letusan freatik 1979 dan pelepasan gas CO2.',
        'image' => 'img/hiking-MD6TD8Y.jpg',
        'lat' => -7.227,
        'lon' => 109.880
    ],
    [
        'slug' => 'curug-sirawe-bitingan',
        'name' => 'Curug Sirawe Bitingan',
        'type' => 'Geosite Air Terjun',
        'description' => 'Air terjun tinggi bersusun di Dusun Bitingan dengan perpaduan air hangat dan dingin.',
        'image' => 'img/long-expo-of-mooney-falls-in-supai-az_t20_6wlPR9.jpg',
        'lat' => -7.195,
        'lon' => 109.878
    ],
    [
        'slug' => 'graben-pagerkandang',
        'name' => 'Graben Pagerkandang',
        'type' => 'Geosite Lanskap',
        'description' => 'Lembah patahan graben yang membentuk bentang alam depresi di utara Dieng.',
        'image' => 'img/nature-outdoors-shadow-forest-light-camping-adventure-woods-hike_t20_QoyZ0j.jpg',
        'lat' => -7.200,
        'lon' => 109.890
    ],
    [
        'slug' => 'kawah-candradimuka',
        'name' => 'Kawah Candradimuka',
        'type' => 'Geosite Vulkanik',
        'description' => 'Kawah aktif dengan fumarol bersuhu tinggi yang lekat dengan legenda Gatotkaca.',
        'image' => 'img/hiking-SSCTN7Z.jpg',
        'lat' => -7.210,
        'lon' => 109.865
    ],
    [
        'slug' => 'gunung-pangonan',
        'name' => 'Gunung Pangonan',
        'type' => 'Geosite Savana',
        'description' => 'Bukit kerucut dengan savana luas bekas kawah purba di barat Telaga Warna.',
        'image' => 'img/hiker-with-backpack-in-the-nature-PH527Y9.JPG',
        'lat' => -7.218,
        'lon' => 109.900
    ],
    [
        'slug' => 'kawah-sileri',
        'name' => 'Kawah Sileri',
        'type' => 'Geosite Vulkanik',
        'description' => 'Kawah lumpur vulkanik paling aktif di Dieng dengan air keabu-abuan menyerupai air leri.',
        'image' => 'img/a-mountaineer-skier-YMXLGSV.jpg',
        'lat' => -7.198,
        'lon' => 109.888
    ],
    [
        'slug' => 'nagasari',
        'name' => 'Kerucut Vulkanik Nagasari',
        'type' => 'Geosite Vulkanik',
        'description' => 'Kubah lava andesit yang membentuk perbukitan kerucut vulkanik khas kompleks Dieng.',
        'image' => 'img/hiking-6RQMGU4.jpg',
        'lat' => -7.225,
        'lon' => 109.870
    ],
    [
        'slug' => 'telaga-dringo',
        'name' => 'Telaga Dringo',
        'type' => 'Geosite Danau',
        'description' => 'Danau kawah vulkanik tertinggi di Dieng berlatar perbukitan hijau alami.',
        'image' => 'img/traveler-siting-on-edge-and-looks-at-mountains-at-sunset_t20_YQ1OO4.jpg',
        'lat' => -7.212,
        'lon' => 109.855
    ],
    [
        'slug' => 'telaga-merdada',
        'name' => 'Telaga Merdada',
        'type' => 'Geosite Danau',
        'description' => 'Danau kawah terluas di Dataran Tinggi Dieng yang dikelilingi lereng perkebunan berundak.',
        'image' => 'img/young-woman-hikes-in-the-mountain-with-beautiful-v-7Y2TRSC.jpg',
        'lat' => -7.210,
        'lon' => 109.870
    ]
];

// Transform to GeoJSON-like features array for JavaScript
$features = [];

// Add Destinations
foreach ($destinations as $d) {
    $features[] = [
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [(float)$d['lon'], (float)$d['lat']]
        ],
        'properties' => [
            'name' => $d['name'],
            'category' => 'tour',
            'type' => $d['type'],
            'description' => $d['description'],
            'image' => $d['image'],
            'url' => 'index.php?page=destination&slug=' . $d['slug']
        ]
    ];
}

// Add Accommodations
foreach ($accommodations as $acc) {
    // Generate slight pseudo-random offset around Dieng center if lat/lon not stored
    $features[] = [
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [
                isset($acc['longitude']) && $acc['longitude'] ? (float)$acc['longitude'] : 109.900 + (crc32($acc['name']) % 50) / 1000,
                isset($acc['latitude']) && $acc['latitude'] ? (float)$acc['latitude'] : -7.210 + (crc32($acc['location'] ?? $acc['name']) % 50) / 1000
            ]
        ],
        'properties' => [
            'name' => $acc['name'],
            'category' => 'accommodation',
            'type' => $acc['type'] ?? 'Akomodasi',
            'description' => $acc['description'] ?? '',
            'image' => !empty($acc['image']) ? 'img/' . $acc['image'] : 'img/default-hotel.jpg',
            'url' => 'index.php?page=accommodation_detail&id=' . $acc['id']
        ]
    ];
}

// Add Culinary
foreach ($culinary as $cul) {
    $features[] = [
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [
                isset($cul['longitude']) && $cul['longitude'] ? (float)$cul['longitude'] : 109.910 + (crc32($cul['name']) % 50) / 1000,
                isset($cul['latitude']) && $cul['latitude'] ? (float)$cul['latitude'] : -7.220 + (crc32($cul['location'] ?? $cul['name']) % 50) / 1000
            ]
        ],
        'properties' => [
            'name' => $cul['name'],
            'category' => 'culinary',
            'type' => 'Kuliner Khas',
            'description' => $cul['description'] ?? '',
            'image' => !empty($cul['image']) ? 'img/' . $cul['image'] : 'img/default-food.jpg',
            'url' => 'index.php?page=culinary'
        ]
    ];
}

// Add Transportation
foreach ($transportations as $trans) {
    $features[] = [
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [
                isset($trans['longitude']) && $trans['longitude'] ? (float)$trans['longitude'] : 109.895 + (crc32($trans['name']) % 40) / 1000,
                isset($trans['latitude']) && $trans['latitude'] ? (float)$trans['latitude'] : -7.225 + (crc32($trans['name']) % 40) / 1000
            ]
        ],
        'properties' => [
            'name' => $trans['name'],
            'category' => 'transportation',
            'type' => $trans['type'] ?? 'Transportasi',
            'description' => $trans['description'] ?? '',
            'image' => !empty($trans['image']) ? 'img/' . $trans['image'] : 'img/default-transport.jpg',
            'url' => 'index.php?page=transportation_detail&id=' . $trans['id']
        ]
    ];
}

$mapFeaturesJson = json_encode([
    'type' => 'FeatureCollection',
    'features' => $features
]);

require 'views/desktop/map.php';
