<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? 'id'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hollynice Ticket - Banjarnegara</title>
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
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                        },
                        brand: '#0d9488'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'system-ui', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'Poppins', 'sans-serif'],
                    }
                }
            }
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        h1, h2, h3, .font-heading { font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; }
        .hero-pattern {
            background-color: #f3f4f6;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen pb-24 md:pb-0">

<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo Section -->
            <div class="flex items-center">
                <a href="index.php" class="flex-shrink-0 flex items-center">
                    <img src="img/logo.jpg" alt="Hollynice Logo" class="h-16 w-auto mr-2 rounded">
                </a>
            </div>

            <!-- Right Section: Menu + Icons -->
            <div class="flex items-center">
                <!-- Navigation Menu -->
                <div class="hidden md:flex md:space-x-8 items-center mr-8">
                    <a href="index.php" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium"><?= trans('Home', 'Beranda') ?></a>
                    
                    <!-- Tiket Wisata Banjarnegara -->
                    <div class="relative group">
                        <button class="text-gray-500 group-hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center outline-none">
                            <span><?= trans('Banjarnegara Tourism Tickets', 'Tiket Wisata Banjarnegara') ?></span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-0 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden group-hover:block z-50">
                            <div class="py-1">
                                <a href="index.php?type=tour" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600"><?= trans('Tour Packages', 'Tiket Paket Wisata') ?></a>
                                <a href="index.php?type=event" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600"><?= trans('Events', 'Tiket Event/ Acara Wisata') ?></a>
                                <a href="index.php?type=entrance" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600"><?= trans('Entrance Tickets', 'Tiket Masuk Lokasi Wisata') ?></a>
                            </div>
                        </div>
                    </div>

                    <!-- Penginapan & Kuliner -->
                    <div class="relative group">
                        <button class="text-gray-500 group-hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center outline-none">
                            <span><?= trans('Accommodations & Culinary', 'Penginapan & Kuliner') ?></span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden group-hover:block z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Penginapan</div>
                                <a href="index.php?page=accommodation&type=hotel" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600">Hotel</a>
                                <a href="index.php?page=accommodation&type=homestay" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600">Homestay</a>
                                <a href="index.php?page=accommodation&type=villa" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600">Villa</a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Kuliner</div>
                                <a href="index.php?page=culinary" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600">Kuliner Legendaris</a>
                                <a href="index.php?page=souvenir" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600">Oleh-oleh & Souvenir</a>
                                <a href="index.php?page=transportation" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600">Jasa Transportasi</a>
                            </div>
                        </div>
                    </div>

                    <!-- Peta Destinasi -->
                    <a href="index.php?page=map" class="text-gray-900 hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium"><?= trans('Destination Map', 'Peta Destinasi') ?></a>

                    <!-- Info -->
                    <div class="relative group">
                        <button class="text-gray-500 group-hover:text-teal-600 px-3 py-2 rounded-md text-sm font-medium inline-flex items-center outline-none">
                            <span><?= trans('Info', 'Info') ?></span>
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden group-hover:block z-50">
                            <div class="py-1">
                                <a href="index.php?page=how_to_buy" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600"><?= trans('How to Buy', 'Cara Pembelian Tiket') ?></a>
                                <a href="index.php?page=verify_ticket" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600"><?= trans('Verify Ticket', 'Verifikasi Tiket') ?></a>
                                <a href="index.php?page=contacts" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-600"><?= trans('Important Contacts', 'Kontak Penting') ?></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User/Cart Icons -->
                <div class="flex items-center space-x-4">
                <!-- Language Switcher -->
                <div class="text-sm">
                    <a href="index.php?page=switch_lang&lang=id" class="<?= ($_SESSION['lang']??'id') == 'id' ? 'font-bold text-teal-600' : 'text-gray-400' ?>">ID</a>
                    <span class="text-gray-300">|</span>
                    <a href="index.php?page=switch_lang&lang=en" class="<?= ($_SESSION['lang']??'id') == 'en' ? 'font-bold text-teal-600' : 'text-gray-400' ?>">EN</a>
                </div>

                <?php if (isLoggedIn()): ?>
                    <a href="index.php?page=cart" class="text-gray-500 hover:text-teal-600">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <div class="relative group">
                        <button class="text-gray-500 hover:text-teal-600 font-medium">
                            <?= $_SESSION['username'] ?>
                        </button>
                        <div class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 hidden group-hover:block border border-gray-100">
                            <?php if (isAdmin()): ?>
                                <a href="index.php?page=admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <?php endif; ?>
                            <?php if (isOperator()): ?>
                                <a href="index.php?page=operator" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Scanner / POS</a>
                            <?php endif; ?>
                            <a href="index.php?page=logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="index.php?page=login" class="text-teal-600 hover:text-teal-700 font-medium"><?= trans('Login', 'Masuk') ?></a>
                    <a href="index.php?page=register" class="bg-teal-600 text-white px-4 py-2 rounded-full hover:bg-teal-700 text-sm font-medium transition"><?= trans('Register', 'Daftar') ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main class="flex-grow">
