<?php
// Fetch products
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();

// Mobile Layout
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hollynice Mobile</title>
    <link rel="manifest" href="manifest.json">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('sw.js').then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100 pb-20">

    <!-- Mobile Header -->
    <div class="bg-teal-600 px-4 py-4 rounded-b-3xl shadow-lg sticky top-0 z-50">
        <div class="flex justify-between items-center text-white">
            <div>
                <span class="text-xs opacity-80"><?= trans('Welcome back,', 'Selamat datang,') ?></span>
                <h1 class="font-bold text-lg"><?= isLoggedIn() ? $_SESSION['username'] : 'Guest' ?></h1>
            </div>
            <a href="index.php?page=cart" class="relative bg-white/20 p-2 rounded-full">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
        <!-- Search Bar -->
        <div class="mt-4">
            <input type="text" placeholder="<?= trans('Search destination...', 'Cari tujuan...') ?>" class="w-full rounded-full py-2 px-4 text-sm text-gray-800 focus:outline-none shadow-inner">
        </div>
    </div>

    <!-- Categories / Filter -->
    <div class="mt-6 px-4">
        <div class="flex space-x-4 overflow-x-auto hide-scrollbar pb-2">
            <button class="flex-shrink-0 bg-teal-600 text-white px-4 py-2 rounded-full text-xs font-bold shadow-md">All</button>
            <button class="flex-shrink-0 bg-white text-gray-600 px-4 py-2 rounded-full text-xs font-bold shadow-sm">Tours</button>
            <button class="flex-shrink-0 bg-white text-gray-600 px-4 py-2 rounded-full text-xs font-bold shadow-sm">Events</button>
            <button class="flex-shrink-0 bg-white text-gray-600 px-4 py-2 rounded-full text-xs font-bold shadow-sm">Entrance</button>
            <button class="flex-shrink-0 bg-white text-gray-600 px-4 py-2 rounded-full text-xs font-bold shadow-sm" onclick="window.location='index.php?page=souvenir'"><?= trans('Souvenirs', 'Oleh-oleh') ?></button>
        </div>
    </div>

    <!-- Featured (Horizontal Scroll) -->
    <div class="mt-6 pl-4">
        <h2 class="font-bold text-gray-800 text-lg mb-3"><?= trans('Popular', 'Populer') ?></h2>
        <div class="flex space-x-4 overflow-x-auto hide-scrollbar pb-4 pr-4">
            <?php foreach ($products as $product): ?>
                <div class="w-64 flex-shrink-0 bg-white rounded-xl shadow-md overflow-hidden relative" onclick="window.location='index.php?page=product&id=<?= $product['id'] ?>'">
                    <img src="img/<?= $product['image'] ?>" class="h-32 w-full object-cover">
                    <div class="p-3">
                        <h3 class="font-bold text-sm truncate"><?= trans($product['name_en'], $product['name_id']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1"><?= formatRupiah($product['price']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Vertical List -->
    <div class="mt-2 px-4">
        <h2 class="font-bold text-gray-800 text-lg mb-3"><?= trans('All Tickets', 'Semua Tiket') ?></h2>
        <div class="space-y-4">
            <?php foreach ($products as $product): ?>
                <div class="bg-white p-3 rounded-xl shadow-sm flex space-x-3" onclick="window.location='index.php?page=product&id=<?= $product['id'] ?>'">
                    <img src="img/<?= $product['image'] ?>" class="w-20 h-20 rounded-lg object-cover bg-gray-200">
                    <div class="flex-1 flex flex-col justify-center">
                        <h3 class="font-bold text-sm text-gray-800"><?= trans($product['name_en'], $product['name_id']) ?></h3>
                        <span class="text-xs text-orange-500 font-semibold bg-orange-100 w-max px-2 py-0.5 rounded mt-1"><?= $product['type'] ?></span>
                        <div class="flex justify-between items-end mt-2">
                            <span class="font-bold text-teal-600 text-sm"><?= formatRupiah($product['price']) ?></span>
                            <button class="bg-teal-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-md">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom Nav -->
    <?php include 'views/mobile/bottom_nav.php'; ?>

</body>
</html>
