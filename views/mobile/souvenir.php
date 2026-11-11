<?php
// WhatsApp Number
$wa_number = "6281234567890"; // Ganti dengan nomor WhatsApp admin yang asli
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oleh-oleh Khas Banjarnegara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 pb-10">

    <!-- Header -->
    <div class="bg-teal-600 text-white px-4 py-4 sticky top-0 z-50 shadow-md flex items-center">
        <a href="index.php" class="mr-4 p-2 rounded-full hover:bg-teal-700 transition">
            <i class="fas fa-arrow-left text-lg"></i>
        </a>
        <h1 class="font-bold text-lg"><?= trans('Souvenirs', 'Oleh-oleh Khas') ?></h1>
    </div>

    <!-- Banner / Intro -->
    <div class="bg-white p-6 mb-4 shadow-sm text-center">
        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-gift text-teal-600 text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">Cindera Mata Banjarnegara</h2>
        <p class="text-sm text-gray-500">Bawa pulang kenangan manis dari Banjarnegara dengan produk-produk khas terbaik kami.</p>
    </div>

    <!-- Product Grid -->
    <div class="px-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($souvenirs as $item): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
                    <div class="relative h-32 md:h-48 bg-gray-200">
                        <img src="img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-3 flex-1 flex flex-col">
                        <h3 class="font-bold text-sm text-gray-800 mb-1 line-clamp-2"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="text-xs text-gray-500 mb-2 line-clamp-2 flex-1"><?= htmlspecialchars($item['description']) ?></p>
                        <div class="mt-auto">
                            <p class="text-teal-600 font-bold text-sm mb-2"><?= is_numeric($item['price']) ? 'Rp ' . number_format($item['price'], 0, ',', '.') : $item['price'] ?></p>
                            <a href="https://wa.me/<?= $wa_number ?>?text=Halo,%20saya%20tertarik%20membeli%20<?= urlencode($item['name']) ?>" 
                               target="_blank"
                               class="block w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 px-2 rounded-lg text-center transition-colors duration-300 flex items-center justify-center gap-1">
                                <i class="fab fa-whatsapp text-sm"></i> Beli
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer Note -->
    <div class="mt-8 px-4 text-center text-xs text-gray-400">
        <p>&copy; <?= date('Y') ?> Hollynice Ticket. All rights reserved.</p>
        <p class="mt-1">Developed by <a href="https://www.clasnet.co.id" target="_blank" class="text-teal-600 hover:underline">Clasnet</a></p>
    </div>

</body>
</html>
