<?php
require_once 'config/db.php';

echo "<h2>Installing Hollynice Ticket System...</h2>";

try {
    // 1. Create Users Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'operator', 'customer') DEFAULT 'customer',
        full_name VARCHAR(100),
        email VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'users' created/checked.<br>";

    // 2. Create Products Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name_en VARCHAR(100) NOT NULL,
        name_id VARCHAR(100) NOT NULL,
        description_en TEXT,
        description_id TEXT,
        price DECIMAL(10, 2) NOT NULL,
        type ENUM('tour', 'entrance', 'event') NOT NULL,
        image VARCHAR(255),
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
        
        -- Event Specific Columns
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
        price_variants JSON, -- Stores multiple ticket types e.g. [{'name':'VIP','price':500000}, {'name':'Reg','price':100000}]
        
        -- Entrance Ticket Specific Columns
        opening_hours_en TEXT,
        opening_hours_id TEXT,
        address_en TEXT,
        address_id TEXT,
        exclusions_en TEXT,
        exclusions_id TEXT,
        terms_en TEXT, -- Ticket specific terms (refund, validity)
        terms_id TEXT,
        contact_phone VARCHAR(20),
        contact_email VARCHAR(100),

        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Table 'products' created/checked.<br>";

    // Ensure new columns exist (for existing installations)
    $columns = [
        "ADD COLUMN duration VARCHAR(100)",
        "ADD COLUMN itinerary_en TEXT",
        "ADD COLUMN itinerary_id TEXT",
        "ADD COLUMN facilities_en TEXT",
        "ADD COLUMN facilities_id TEXT",
        "ADD COLUMN policy_en TEXT",
        "ADD COLUMN policy_id TEXT",
        "ADD COLUMN rating DECIMAL(3, 1) DEFAULT 5.0",
        "ADD COLUMN review_count INT DEFAULT 0",
        "ADD COLUMN latitude DECIMAL(10, 8)",
        "ADD COLUMN longitude DECIMAL(11, 8)",
        "ADD COLUMN tagline_en VARCHAR(255)",
        "ADD COLUMN tagline_id VARCHAR(255)",
        "ADD COLUMN event_date DATETIME",
        "ADD COLUMN event_end_date DATETIME",
        "ADD COLUMN meeting_point_en TEXT",
        "ADD COLUMN meeting_point_id TEXT",
        "ADD COLUMN accessibility_en TEXT",
        "ADD COLUMN accessibility_id TEXT",
        "ADD COLUMN what_to_bring_en TEXT",
        "ADD COLUMN what_to_bring_id TEXT",
        "ADD COLUMN quota INT DEFAULT 100",
        "ADD COLUMN price_variants JSON",
        "ADD COLUMN opening_hours_en TEXT",
        "ADD COLUMN opening_hours_id TEXT",
        "ADD COLUMN address_en TEXT",
        "ADD COLUMN address_id TEXT",
        "ADD COLUMN exclusions_en TEXT",
        "ADD COLUMN exclusions_id TEXT",
        "ADD COLUMN terms_en TEXT",
        "ADD COLUMN terms_id TEXT",
        "ADD COLUMN contact_phone VARCHAR(20)",
        "ADD COLUMN contact_email VARCHAR(100)"
    ];

    foreach ($columns as $colSql) {
        try {
            $pdo->exec("ALTER TABLE products $colSql");
        } catch (Exception $e) {
            // Column likely exists, ignore
        }
    }
    echo "Table 'products' schema updated.<br>";

    // 3. Create Product Images Table (Gallery)
    $pdo->exec("CREATE TABLE IF NOT EXISTS product_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");
    echo "Table 'product_images' created/checked.<br>";

    // 4. Create Orders Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending',
        payment_method VARCHAR(50),
        transaction_id VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )");
    echo "Table 'orders' created/checked.<br>";

    // 5. Create Order Items Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        visit_date DATE,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    )");
    echo "Table 'order_items' created/checked.<br>";

    // 6. Create Tickets Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS tickets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_item_id INT,
        ticket_code VARCHAR(100) NOT NULL UNIQUE,
        status ENUM('valid', 'used') DEFAULT 'valid',
        used_at TIMESTAMP NULL,
        FOREIGN KEY (order_item_id) REFERENCES order_items(id)
    )");
    echo "Table 'tickets' created/checked.<br>";

    // Seed Users
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $check = $pdo->query("SELECT count(*) FROM users WHERE username = 'admin'")->fetchColumn();
    if ($check == 0) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', $password, 'admin', 'Super Admin', 'admin@hollynice.com']);
        
        $passOp = password_hash('operator123', PASSWORD_DEFAULT);
        $stmt->execute(['operator', $passOp, 'operator', 'Ticket Operator', 'operator@hollynice.com']);

        $passUser = password_hash('user123', PASSWORD_DEFAULT);
        $stmt->execute(['user', $passUser, 'customer', 'John Doe', 'john@example.com']);
        echo "Default users created.<br>";
    }

    // Seed Products (Clear existing and re-seed with rich data)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE tickets");
    $pdo->exec("TRUNCATE TABLE order_items");
    $pdo->exec("TRUNCATE TABLE orders");
    $pdo->exec("TRUNCATE TABLE product_images");
    $pdo->exec("TRUNCATE TABLE products");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $products = [
        [
            'name_en' => 'Dieng Plateau Golden Sunrise Tour',
            'name_id' => 'Paket Tur Sunrise Emas Dieng Plateau',
            'desc_en' => 'Witness the magical sunrise at Sikunir Hill and explore the ancient temples of Dieng. This tour offers a complete experience of the "Abode of the Gods".',
            'desc_id' => 'Saksikan matahari terbit yang ajaib di Bukit Sikunir dan jelajahi candi-candi kuno Dieng. Tur ini menawarkan pengalaman lengkap di "Negeri Di Atas Awan".',
            'price' => 350000,
            'type' => 'tour',
            'image' => 'overlooking-jurrasic-park-QFRXFY7.jpg',
            'duration' => '2 Days 1 Night',
            'itinerary_en' => "Day 1: Pick up at Banjarnegara, Lunch, Visit Arjuna Temple Complex, Check-in Homestay.\nDay 2: 3:00 AM Sunrise Trek to Sikunir, Breakfast, Visit Color Lake (Telaga Warna), Sikidang Crater, Souvenir Shopping, Drop off.",
            'itinerary_id' => "Hari 1: Penjemputan di Banjarnegara, Makan Siang, Kunjungan Kompleks Candi Arjuna, Check-in Homestay.\nHari 2: 03:00 Trekking Sunrise ke Sikunir, Sarapan, Kunjungan Telaga Warna, Kawah Sikidang, Belanja Oleh-oleh, Pengantaran.",
            'facilities_en' => "- Private Transport (AC)\n- Homestay (1 Night)\n- Entrance Tickets to all spots\n- Mineral Water & Snacks\n- Local Guide",
            'facilities_id' => "- Transportasi Pribadi (AC)\n- Homestay (1 Malam)\n- Tiket Masuk semua lokasi\n- Air Mineral & Snack\n- Pemandu Lokal",
            'policy_en' => "Cancel up to 24 hours in advance for a full refund. Late cancellation fee: 50%.",
            'policy_id' => "Pembatalan hingga 24 jam sebelumnya untuk pengembalian dana penuh. Biaya pembatalan terlambat: 50%.",
            'rating' => 4.9,
            'review_count' => 128,
            'latitude' => -7.20500000,
            'longitude' => 109.91400000,
            'gallery' => ['hiker-PBPW4HT.jpg', 'simon-fanger-6_ee0s7d0Ck-unsplash.jpg']
        ],
        [
            'name_en' => 'Serayu River Rafting Adventure',
            'name_id' => 'Petualangan Arung Jeram Sungai Serayu',
            'desc_en' => 'Adrenaline pumping rafting experience in Serayu River. Suitable for beginners and professionals. Enjoy the scenic view along the river.',
            'desc_id' => 'Pengalaman arung jeram yang memacu adrenalin di Sungai Serayu. Cocok untuk pemula dan profesional. Nikmati pemandangan indah sepanjang sungai.',
            'price' => 250000,
            'type' => 'tour',
            'image' => 'iceland-waterfall_t20_Qa4K36.jpg', 
            'duration' => '4 Hours',
            'itinerary_en' => "08:00 AM: Meeting Point at Basecamp\n08:30 AM: Briefing & Equipment Fitting\n09:00 AM: Start Rafting (12 km)\n11:30 AM: Finish Rafting, Shower & Lunch\n01:00 PM: End of Service",
            'itinerary_id' => "08:00: Titik Kumpul di Basecamp\n08:30: Pengarahan & Pasang Peralatan\n09:00: Mulai Rafting (12 km)\n11:30: Selesai Rafting, Mandi & Makan Siang\n13:00: Selesai",
            'facilities_en' => "- Rafting Equipment (Boat, Helmet, Paddle, Vest)\n- River Guide\n- Rescue Team\n- Lunch & Coconut Drink\n- Transport to Start Point\n- Insurance",
            'facilities_id' => "- Peralatan Rafting (Perahu, Helm, Dayung, Pelampung)\n- Pemandu Sungai\n- Tim Penyelamat\n- Makan Siang & Kelapa Muda\n- Transportasi ke Titik Awal\n- Asuransi",
            'policy_en' => "Not recommended for pregnant women or people with heart conditions. Minimum age: 7 years.",
            'policy_id' => "Tidak disarankan untuk wanita hamil atau penderita penyakit jantung. Usia minimum: 7 tahun.",
            'rating' => 4.8,
            'review_count' => 85,
            'latitude' => -7.38200000,
            'longitude' => 109.69200000,
            'gallery' => ['long-expo-of-mooney-falls-in-supai-az_t20_6wlPR9.jpg', 'nature-outdoors-shadow-forest-light-camping-adventure-woods-hike_t20_QoyZ0j.jpg']
        ],
        [
            'name_en' => 'Curug Pitu Waterfall Exploration',
            'name_id' => 'Eksplorasi Air Terjun Curug Pitu',
            'desc_en' => 'Discover the hidden gem of Banjarnegara, the 7-level waterfall Curug Pitu. Trek through the lush forest and swim in the natural pools.',
            'desc_id' => 'Temukan permata tersembunyi Banjarnegara, air terjun 7 tingkat Curug Pitu. Trekking melalui hutan rimbun dan berenang di kolam alami.',
            'price' => 75000,
            'type' => 'entrance',
            'image' => 'autumn-waterfall-PJH8WT9.jpg',
            'duration' => '1 Day',
            'itinerary_en' => "Flexible time. Best visited between 8 AM - 3 PM.",
            'itinerary_id' => "Waktu fleksibel. Waktu kunjungan terbaik antara 08:00 - 15:00.",
            'facilities_en' => "- Entrance Ticket\n- Parking Area\n- Toilet & Changing Room",
            'facilities_id' => "- Tiket Masuk\n- Area Parkir\n- Toilet & Ruang Ganti",
            'policy_en' => "Non-refundable ticket.",
            'policy_id' => "Tiket tidak dapat diuangkan kembali.",
            'rating' => 4.5,
            'review_count' => 42,
            'latitude' => -7.34500000,
            'longitude' => 109.72000000,
            'gallery' => ['young-woman-hikes-in-the-mountain-with-beautiful-v-7Y2TRSC.jpg', 'logo.jpg']
        ],
        [
            'name_en' => 'Dieng Culture Festival 2026',
            'name_id' => 'Dieng Culture Festival 2026',
            'desc_en' => 'Experience the magic of the Dieng Culture Festival, an annual celebration of culture and nature in the highlands. Witness the famous Jazz Clouds, Lantern Festival, and the sacred Dreadlocks Cutting Ritual.',
            'desc_id' => 'Rasakan keajaiban Dieng Culture Festival, perayaan tahunan budaya dan alam di dataran tinggi. Saksikan Jazz Atas Awan, Festival Lampion, dan Ritual Ruwat Rambut Gembel yang sakral.',
            'price' => 350000,
            'type' => 'event',
            'image' => 'traveler-siting-on-edge-and-looks-at-mountains-at-sunset_t20_YQ1OO4.jpg',
            'duration' => '3 Days Event',
            'itinerary_en' => "Day 1: Opening Ceremony & Jazz Clouds\nDay 2: Cultural Parade & Lantern Festival\nDay 3: Ruwat Rambut Gembel Ritual",
            'itinerary_id' => "Hari 1: Upacara Pembukaan & Jazz Atas Awan\nHari 2: Kirab Budaya & Festival Lampion\nHari 3: Ritual Ruwat Rambut Gembel",
            'facilities_en' => "- All-Access Pass\n- Merchandise T-Shirt\n- Lantern\n- Camping Ground Access",
            'facilities_id' => "- Tiket Terusan\n- Kaos Merchandise\n- Lampion\n- Akses Camping Ground",
            'policy_en' => "Strictly non-refundable. ID card required for entry. No pets allowed.",
            'policy_id' => "Sama sekali tidak dapat dikembalikan. KTP diperlukan untuk masuk. Dilarang membawa hewan peliharaan.",
            'rating' => 5.0,
            'review_count' => 320,
            'latitude' => -7.20500000,
            'longitude' => 109.91400000,
            'gallery' => ['logo.jpg', 'overlooking-jurrasic-park-QFRXFY7.jpg'],
            'tagline_en' => "The Jazz Above The Clouds",
            'tagline_id' => "Jazz Atas Awan",
            'event_date' => "2026-08-01 08:00:00",
            'event_end_date' => "2026-08-03 12:00:00",
            'meeting_point_en' => "Dieng Kulon, Batur, Banjarnegara",
            'meeting_point_id' => "Dieng Kulon, Batur, Banjarnegara",
            'accessibility_en' => "Wheelchair accessible in main stage area.",
            'accessibility_id' => "Akses kursi roda di area panggung utama.",
            'what_to_bring_en' => "Warm jacket (5°C), raincoat, comfortable shoes.",
            'what_to_bring_id' => "Jaket tebal (5°C), jas hujan, sepatu nyaman.",
            'quota' => 500,
            'price_variants' => [
                ["name" => "Regular Pass", "price" => 350000, "info" => "Access to all shows"],
                ["name" => "VIP Pass", "price" => 750000, "info" => "Front row + Exclusive Merch"],
                ["name" => "Camping Package", "price" => 500000, "info" => "Regular Pass + Tent Rent"]
            ]
        ],
        [
            'name_en' => 'Serayu River Festival 2026',
            'name_id' => 'Festival Sungai Serayu 2026',
            'desc_en' => 'Join the biggest river festival in Central Java! Featuring rafting competitions, traditional boat parades, and a massive riverside culinary bazaar.',
            'desc_id' => 'Ikuti festival sungai terbesar di Jawa Tengah! Menampilkan kompetisi arung jeram, parade perahu tradisional, dan bazar kuliner tepi sungai yang besar.',
            'price' => 50000,
            'type' => 'event',
            'image' => 'iceland-waterfall_t20_Qa4K36.jpg',
            'duration' => '2 Days Event',
            'itinerary_en' => "Day 1: Rafting Competition Heats, Opening Ceremony\nDay 2: Finals, Boat Parade, Awarding Night",
            'itinerary_id' => "Hari 1: Penyisihan Lomba Rafting, Upacara Pembukaan\nHari 2: Final, Parade Perahu, Malam Penganugerahan",
            'facilities_en' => "- Festival Entry\n- Welcome Drink\n- Concert Access",
            'facilities_id' => "- Masuk Festival\n- Minuman Selamat Datang\n- Akses Konser",
            'policy_en' => "Tickets are non-refundable. Children under 5 years free.",
            'policy_id' => "Tiket tidak dapat dikembalikan. Anak di bawah 5 tahun gratis.",
            'rating' => 4.7,
            'review_count' => 150,
            'latitude' => -7.38200000,
            'longitude' => 109.69200000,
            'gallery' => ['long-expo-of-mooney-falls-in-supai-az_t20_6wlPR9.jpg'],
            'tagline_en' => "The Spirit of Serayu",
            'tagline_id' => "Semangat Serayu",
            'event_date' => "2026-09-15 09:00:00",
            'event_end_date' => "2026-09-16 22:00:00",
            'meeting_point_en' => "Serayu River Park, Banjarnegara",
            'meeting_point_id' => "Taman Sungai Serayu, Banjarnegara",
            'accessibility_en' => "Accessible parking available. Uneven terrain near river bank.",
            'accessibility_id' => "Parkir aksesibel tersedia. Tanah tidak rata di dekat tepi sungai.",
            'what_to_bring_en' => "Sunscreen, hat, comfortable clothes.",
            'what_to_bring_id' => "Tabir surya, topi, pakaian nyaman.",
            'quota' => 2000,
            'price_variants' => [
                ["name" => "Spectator", "price" => 50000, "info" => "Festival Area Access"],
                ["name" => "Rafting Team", "price" => 1500000, "info" => "Competition Entry for 1 Team (5 Pax)"],
                ["name" => "VIP Tribune", "price" => 150000, "info" => "Best View + Snack Box"]
            ]
        ],
        [
            'name_en' => 'Banjarnegara Coffee Harvest 2026',
            'name_id' => 'Panen Raya Kopi Banjarnegara 2026',
            'desc_en' => 'Celebrate the harvest season with local coffee farmers. Learn the process from bean to cup, participate in cupping sessions, and enjoy the cool mountain breeze.',
            'desc_id' => 'Rayakan musim panen bersama petani kopi lokal. Pelajari proses dari biji hingga cangkir, ikuti sesi cupping, dan nikmati sejuknya angin pegunungan.',
            'price' => 100000,
            'type' => 'event',
            'image' => 'simon-fanger-6_ee0s7d0Ck-unsplash.jpg',
            'duration' => '1 Day Event',
            'itinerary_en' => "09:00 AM: Farm Tour\n11:00 AM: Harvesting Workshop\n01:00 PM: Lunch & Brewing Session\n03:00 PM: Barista Competition",
            'itinerary_id' => "09:00: Tur Kebun\n11:00: Workshop Panen\n13:00: Makan Siang & Sesi Seduh\n15:00: Kompetisi Barista",
            'facilities_en' => "- Farm Tour\n- Unlimited Coffee\n- Traditional Lunch\n- Take-home Beans (200g)",
            'facilities_id' => "- Tur Kebun\n- Kopi Sepuasnya\n- Makan Siang Tradisional\n- Biji Kopi Oleh-oleh (200g)",
            'policy_en' => "Wear comfortable shoes for walking in the garden.",
            'policy_id' => "Gunakan sepatu nyaman untuk berjalan di kebun.",
            'rating' => 4.9,
            'review_count' => 88,
            'latitude' => -7.28500000,
            'longitude' => 109.79000000,
            'gallery' => ['nature-outdoors-shadow-forest-light-camping-adventure-woods-hike_t20_QoyZ0j.jpg'],
            'tagline_en' => "Aroma of The Highlands",
            'tagline_id' => "Aroma Dataran Tinggi",
            'event_date' => "2026-07-20 09:00:00",
            'event_end_date' => "2026-07-20 17:00:00",
            'meeting_point_en' => "Kalibening Coffee Center",
            'meeting_point_id' => "Sentra Kopi Kalibening",
            'accessibility_en' => "Not suitable for wheelchairs due to hilly terrain.",
            'accessibility_id' => "Tidak cocok untuk kursi roda karena medan berbukit.",
            'what_to_bring_en' => "Jacket, camera.",
            'what_to_bring_id' => "Jaket, kamera.",
            'quota' => 100,
            'price_variants' => [
                ["name" => "Visitor", "price" => 100000, "info" => "Tour & Tasting"],
                ["name" => "Workshop Participant", "price" => 250000, "info" => "Full Workshop + Certificate + Beans"],
                ["name" => "Barista Competitor", "price" => 300000, "info" => "Competition Entry Fee"]
            ]
        ],
        [
            'name_en' => 'Taman Wisata Alam Coban Rondo',
            'name_id' => 'Taman Wisata Alam Coban Rondo',
            'desc_en' => 'A stunning waterfall located in the pine forest. Perfect for families and nature lovers. Enjoy the fresh air, beautiful scenery, and various fun activities.',
            'desc_id' => 'Air terjun menakjubkan yang terletak di hutan pinus. Sempurna untuk keluarga dan pecinta alam. Nikmati udara segar, pemandangan indah, dan berbagai aktivitas seru.',
            'price' => 35000,
            'type' => 'entrance',
            'image' => 'autumn-waterfall-PJH8WT9.jpg',
            'duration' => 'Flexible',
            'opening_hours_en' => "Monday - Sunday: 08:00 AM - 05:00 PM\nPublic Holidays: Open",
            'opening_hours_id' => "Senin - Minggu: 08:00 - 17:00 WIB\nHari Libur Nasional: Buka",
            'address_en' => "Jl. Coban Rondo, Krajan, Pandesari, Pujon, Malang, East Java",
            'address_id' => "Jl. Coban Rondo, Krajan, Pandesari, Pujon, Malang, Jawa Timur",
            'facilities_en' => "- Access to Waterfall\n- Flower Garden\n- Playground\n- Toilet & Mushola\n- Picnic Area",
            'facilities_id' => "- Akses ke Air Terjun\n- Taman Bunga\n- Area Bermain Anak\n- Toilet & Mushola\n- Area Piknik",
            'exclusions_en' => "- Flying Fox\n- Bicycle Rental\n- Food & Beverages\n- Parking Fee",
            'exclusions_id' => "- Flying Fox\n- Sewa Sepeda\n- Makanan & Minuman\n- Parkir Kendaraan",
            'policy_en' => "Wear comfortable shoes. No littering. Do not feed wild monkeys.",
            'policy_id' => "Gunakan sepatu yang nyaman. Dilarang membuang sampah sembarangan. Dilarang memberi makan monyet liar.",
            'terms_en' => "Valid for single entry on selected date. Non-refundable. Show e-ticket at the gate.",
            'terms_id' => "Berlaku untuk satu kali masuk pada tanggal yang dipilih. Tidak dapat dikembalikan. Tunjukkan e-tiket di pintu masuk.",
            'rating' => 4.6,
            'review_count' => 1250,
            'latitude' => -7.85200000,
            'longitude' => 112.48300000,
            'gallery' => ['hiker-PBPW4HT.jpg', 'nature-outdoors-shadow-forest-light-camping-adventure-woods-hike_t20_QoyZ0j.jpg'],
            'contact_phone' => "+628123456789",
            'contact_email' => "info@cobanrondo.com",
            'price_variants' => [
                ["name" => "Domestic Adult", "price" => 35000, "info" => "Weekdays & Weekends"],
                ["name" => "Domestic Child", "price" => 20000, "info" => "Age 3-10 Years"],
                ["name" => "Foreigner", "price" => 75000, "info" => "International Tourist"]
            ]
        ],
        [
            'name_en' => 'Serulingmas Zoo Recreation Park',
            'name_id' => 'Taman Rekreasi Margasatwa Serulingmas',
            'desc_en' => 'A family-friendly zoo and recreation park located in the heart of Banjarnegara. Home to various exotic animals including tigers, lions, and crocodiles. Features a swimming pool and playground for kids.',
            'desc_id' => 'Taman rekreasi dan kebun binatang ramah keluarga di jantung Banjarnegara. Rumah bagi berbagai hewan eksotis termasuk harimau, singa, dan buaya. Dilengkapi kolam renang dan taman bermain anak.',
            'price' => 25000,
            'type' => 'entrance',
            'image' => 'taman-rekreasi-margasatwa-serulingmas.jpeg',
            'duration' => 'Flexible',
            'opening_hours_en' => "Monday - Sunday: 08:00 AM - 04:00 PM",
            'opening_hours_id' => "Senin - Minggu: 08:00 - 16:00 WIB",
            'address_en' => "Jl. Selamanik No.35, Kutabanjarnegara, Banjarnegara, Central Java",
            'address_id' => "Jl. Selamanik No.35, Kutabanjarnegara, Banjarnegara, Jawa Tengah",
            'facilities_en' => "- Zoo Area\n- Swimming Pool\n- Kids Playground\n- Gazebo\n- Canteen\n- Toilet & Mushola",
            'facilities_id' => "- Area Kebun Binatang\n- Kolam Renang\n- Taman Bermain Anak\n- Gazebo\n- Kantin\n- Toilet & Mushola",
            'exclusions_en' => "- Animal Feeding\n- Ride Tickets",
            'exclusions_id' => "- Pakan Hewan\n- Tiket Wahana Permainan",
            'policy_en' => "Do not disturb the animals. Throw trash in the bin.",
            'policy_id' => "Dilarang mengganggu hewan. Buang sampah pada tempatnya.",
            'terms_en' => "Ticket valid for one day. Children under 80cm free.",
            'terms_id' => "Tiket berlaku satu hari. Anak di bawah 80cm gratis.",
            'rating' => 4.4,
            'review_count' => 560,
            'latitude' => -7.39700000,
            'longitude' => 109.69800000,
            'gallery' => ['seruling-mas.jpeg'],
            'contact_phone' => "+62286591234",
            'contact_email' => "info@serulingmas.com",
            'price_variants' => [
                ["name" => "Weekday Ticket", "price" => 20000, "info" => "Monday - Friday"],
                ["name" => "Weekend Ticket", "price" => 25000, "info" => "Saturday, Sunday & Holiday"]
            ]
        ],
        [
            'name_en' => 'Arjuna Temple Complex Dieng',
            'name_id' => 'Obyek Wisata Candi Arjuna Dieng',
            'desc_en' => 'Explore the oldest Hindu temple complex in Java, situated in the misty Dieng Plateau. Experience the mystical atmosphere, ancient history, and stunning mountain views.',
            'desc_id' => 'Jelajahi kompleks candi Hindu tertua di Jawa yang terletak di Dataran Tinggi Dieng yang berkabut. Rasakan suasana mistis, sejarah kuno, dan pemandangan pegunungan yang menakjubkan.',
            'price' => 30000,
            'type' => 'entrance',
            'image' => 'candi-arjuna.jpg',
            'duration' => 'Flexible',
            'opening_hours_en' => "Monday - Sunday: 07:00 AM - 05:00 PM",
            'opening_hours_id' => "Senin - Minggu: 07:00 - 17:00 WIB",
            'address_en' => "Dieng Kulon, Batur, Banjarnegara, Central Java",
            'address_id' => "Dieng Kulon, Batur, Banjarnegara, Jawa Tengah",
            'facilities_en' => "- Historical Temple Area\n- Photo Spots\n- Toilet\n- Mushola\n- Parking Area\n- Souvenir Stalls",
            'facilities_id' => "- Area Candi Bersejarah\n- Spot Foto\n- Toilet\n- Mushola\n- Area Parkir\n- Kios Oleh-oleh",
            'exclusions_en' => "- Guide Service\n- Parking Fee\n- Jacket Rental",
            'exclusions_id' => "- Jasa Pemandu\n- Parkir Kendaraan\n- Sewa Jaket",
            'policy_en' => "Maintain politeness. Do not climb the stupas/temples.",
            'policy_id' => "Jaga kesopanan. Dilarang memanjat stupa/candi.",
            'terms_en' => "Ticket valid for single entry.",
            'terms_id' => "Tiket berlaku untuk satu kali masuk.",
            'rating' => 4.8,
            'review_count' => 2100,
            'latitude' => -7.20500000,
            'longitude' => 109.91400000,
            'gallery' => ['overlooking-jurrasic-park-QFRXFY7.jpg'],
            'contact_phone' => "+62286595555",
            'contact_email' => "disparbud@banjarnegarakab.go.id",
            'price_variants' => [
                ["name" => "Domestic Tourist", "price" => 30000, "info" => "WNI"],
                ["name" => "International Tourist", "price" => 75000, "info" => "WNA"]
            ]
        ],
        [
            'name_en' => 'Sangiran Early Man Museum',
            'name_id' => 'Museum Manusia Purba Sangiran',
            'desc_en' => 'Explore the history of human evolution at this UNESCO World Heritage Site. See ancient fossils and learn about prehistoric life in Java.',
            'desc_id' => 'Jelajahi sejarah evolusi manusia di Situs Warisan Dunia UNESCO ini. Lihat fosil kuno dan pelajari kehidupan prasejarah di Jawa.',
            'price' => 15000,
            'type' => 'entrance',
            'image' => 'young-woman-hikes-in-the-mountain-with-beautiful-v-7Y2TRSC.jpg',
            'duration' => '2-3 Hours',
            'opening_hours_en' => "Tuesday - Sunday: 08:00 AM - 04:00 PM\nMonday: Closed",
            'opening_hours_id' => "Selasa - Minggu: 08:00 - 16:00 WIB\nSenin: Tutup",
            'address_en' => "Kebayanan II, Krikilan, Kalijambe, Sragen, Central Java",
            'address_id' => "Kebayanan II, Krikilan, Kalijambe, Sragen, Jawa Tengah",
            'facilities_en' => "- Museum Exhibition\n- Audio Visual Room\n- Souvenir Shop\n- Parking Area\n- Guide",
            'facilities_id' => "- Pameran Museum\n- Ruang Audio Visual\n- Toko Souvenir\n- Area Parkir\n- Pemandu",
            'exclusions_en' => "- Shuttle Car\n- Professional Camera Permit",
            'exclusions_id' => "- Mobil Shuttle\n- Izin Kamera Profesional",
            'policy_en' => "No flash photography inside. No food and drinks in exhibition area.",
            'policy_id' => "Dilarang menggunakan flash kamera di dalam. Dilarang makan dan minum di area pameran.",
            'terms_en' => "Ticket valid on purchase date. Children under 3 years free.",
            'terms_id' => "Tiket berlaku pada tanggal pembelian. Anak di bawah 3 tahun gratis.",
            'rating' => 4.8,
            'review_count' => 890,
            'latitude' => -7.45800000,
            'longitude' => 110.85600000,
            'gallery' => ['logo.jpg'],
            'contact_phone' => "+622716811495",
            'contact_email' => "sangiran@kemdikbud.go.id",
            'price_variants' => [
                ["name" => "Adult (WNI)", "price" => 15000, "info" => "Indonesian Citizen"],
                ["name" => "Tourist (WNA)", "price" => 50000, "info" => "Foreign Tourist"]
            ]
        ],
        [
            'name_en' => 'Rafting Serayu by The Pikas',
            'name_id' => 'Arung Jeram Serayu by The Pikas',
            'desc_en' => 'Experience the thrill of white water rafting on the Serayu River with The Pikas Artventure. Grade 3+ rapids suitable for beginners and professionals. Enjoy the beautiful scenery of Banjarnegara while pumping your adrenaline.',
            'desc_id' => 'Rasakan sensasi arung jeram di Sungai Serayu bersama The Pikas Artventure. Jeram grade 3+ yang cocok untuk pemula hingga profesional. Nikmati pemandangan indah Banjarnegara sambil memacu adrenalin Anda.',
            'price' => 185000,
            'type' => 'tour',
            'image' => 'autumn-waterfall-PJH8WT9.jpg',
            'duration' => '3-4 Hours',
            'itinerary_en' => "08:00 AM: Arrival & Registration\n08:30 AM: Briefing & Equipment Fitting\n09:00 AM: Start Rafting (14km)\n12:00 PM: Finish & Lunch\n01:00 PM: Documentation & Clean up",
            'itinerary_id' => "08:00: Kedatangan & Registrasi\n08:30: Pengarahan & Persiapan Alat\n09:00: Mulai Arung Jeram (14km)\n12:00: Selesai & Makan Siang\n13:00: Dokumentasi & Bersih-bersih",
            'facilities_en' => "- International Standard Equipment\n- Professional Guide\n- Rescue Team\n- Local Transport (Start-Finish)\n- Buffet Lunch\n- Insurance\n- Shower & Toilet",
            'facilities_id' => "- Peralatan Standar Internasional\n- Pemandu Profesional\n- Tim Penyelamat\n- Transportasi Lokal (Start-Finish)\n- Makan Siang Prasmanan\n- Asuransi\n- Kamar Mandi & Toilet",
            'policy_en' => "Minimum age 7 years old. Not recommended for pregnant women or people with heart conditions.",
            'policy_id' => "Usia minimal 7 tahun. Tidak disarankan untuk wanita hamil atau orang dengan penyakit jantung.",
            'rating' => 4.9,
            'review_count' => 540,
            'latitude' => -7.36300000,
            'longitude' => 109.72800000,
            'gallery' => ['autumn-waterfall-PJH8WT9.jpg'],
            'contact_phone' => "082324674544",
            'contact_email' => "marketing@thepikas.com",
            'address_en' => "The Pikas Artventure Resort, Kutayasa, Madukara, Banjarnegara",
            'address_id' => "The Pikas Artventure Resort, Kutayasa, Madukara, Banjarnegara",
            'price_variants' => [
                ["name" => "Rafting Package 1", "price" => 185000, "info" => "Standard Rafting"],
                ["name" => "Rafting + Outbound", "price" => 250000, "info" => "Rafting & Team Building"]
            ]
        ],
        [
            'name_en' => 'Desa Wisata Blambangan (d’Kuwondogiri)',
            'name_id' => 'Desa Wisata Blambangan (d’Kuwondogiri)',
            'desc_en' => 'An agro-tourism village located 6km west of Banjarnegara. Offers authentic rural experiences including the "Baruklinting" rice field boardwalk, river tubing, historical tours, and culinary delights at Rest Area Gili Lori.',
            'desc_id' => 'Desa wisata berbasis agro yang berjarak 6 km dari pusat kota Banjarnegara. Menawarkan pengalaman pedesaan otentik termasuk jembatan sawah "Baruklinting", tubing sungai, wisata sejarah, dan kuliner di Rest Area Gili Lori.',
            'price' => 5000,
            'type' => 'entrance',
            'image' => 'holly6.jpeg',
            'duration' => 'Flexible',
            'policy_en' => 'Respect local customs. Keep the environment clean.',
            'policy_id' => 'Hormati adat istiadat setempat. Jaga kebersihan lingkungan.',
            'rating' => 4.5,
            'review_count' => 120,
            'opening_hours_en' => '08:00 AM - 05:00 PM',
            'opening_hours_id' => '08:00 - 17:00 WIB',
            'address_en' => 'Blambangan Village, Bawang District, Banjarnegara',
            'address_id' => 'Desa Blambangan, Kec. Bawang, Kab. Banjarnegara',
            'facilities_en' => "- Broadwalk Baruklinting\n- River Tubing\n- Rest Area Gili Lori\n- Outbound Area\n- Homestay\n- Toilet & Mushola",
            'facilities_id' => "- Broadwalk Baruklinting\n- Tubing Kali Belimbing\n- Rest Area Gili Lori\n- Area Outbound\n- Homestay\n- Toilet & Mushola",
            'latitude' => -7.3995,
            'longitude' => 109.6452,
            'gallery' => ['holly6.jpeg', 'holly5.jpeg'],
            'price_variants' => [
                ["name" => "Tiket Masuk (Entrance)", "price" => 5000, "info" => "General Admission"],
                ["name" => "Paket Tubing Kali Belimbing", "price" => 35000, "info" => "River Tubing + Guide"],
                ["name" => "Paket Outbound", "price" => 50000, "info" => "Fun Games + Snack"]
            ],
            'contact_phone' => "081234567890",
            'contact_email' => "info@kuwondogiri.com"
        ]
    ];

    $stmt = $pdo->prepare("INSERT INTO products (name_en, name_id, description_en, description_id, price, type, image, duration, itinerary_en, itinerary_id, facilities_en, facilities_id, policy_en, policy_id, rating, review_count, latitude, longitude, tagline_en, tagline_id, event_date, event_end_date, meeting_point_en, meeting_point_id, accessibility_en, accessibility_id, what_to_bring_en, what_to_bring_id, quota, price_variants, opening_hours_en, opening_hours_id, address_en, address_id, exclusions_en, exclusions_id, terms_en, terms_id, contact_phone, contact_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmtImg = $pdo->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");

    foreach ($products as $p) {
        $stmt->execute([
            $p['name_en'], $p['name_id'], $p['desc_en'], $p['desc_id'], $p['price'], $p['type'], $p['image'],
            $p['duration'], $p['itinerary_en'] ?? null, $p['itinerary_id'] ?? null, $p['facilities_en'], $p['facilities_id'], $p['policy_en'], $p['policy_id'], $p['rating'], $p['review_count'], $p['latitude'], $p['longitude'],
            $p['tagline_en'] ?? null, $p['tagline_id'] ?? null,
            $p['event_date'] ?? null, $p['event_end_date'] ?? null,
            $p['meeting_point_en'] ?? null, $p['meeting_point_id'] ?? null,
            $p['accessibility_en'] ?? null, $p['accessibility_id'] ?? null,
            $p['what_to_bring_en'] ?? null, $p['what_to_bring_id'] ?? null,
            $p['quota'] ?? 100,
            isset($p['price_variants']) ? json_encode($p['price_variants']) : null,
            $p['opening_hours_en'] ?? null, $p['opening_hours_id'] ?? null,
            $p['address_en'] ?? null, $p['address_id'] ?? null,
            $p['exclusions_en'] ?? null, $p['exclusions_id'] ?? null,
            $p['terms_en'] ?? null, $p['terms_id'] ?? null,
            $p['contact_phone'] ?? null, $p['contact_email'] ?? null
        ]);
        
        $productId = $pdo->lastInsertId();
        
        // Insert Gallery
        foreach ($p['gallery'] as $img) {
            $stmtImg->execute([$productId, $img]);
        }
    }
    echo "Dummy products and gallery seeded successfully.<br>";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
