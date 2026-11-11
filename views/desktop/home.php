<?php
// Fetch products
$type = $_GET['type'] ?? null;
if ($type) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE type = ? ORDER BY created_at DESC");
    $stmt->execute([$type]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
}
$products = $stmt->fetchAll();

require 'views/layouts/header.php';
?>

<!-- Hero Section -->
<div class="relative bg-teal-700 h-[500px]">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="img/holly3.jpeg" alt="Banjarnegara">
        <div class="absolute inset-0 bg-teal-900 mix-blend-multiply opacity-40" aria-hidden="true"></div>
    </div>
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
            <?= trans('Explore Banjarnegara Tourism', 'Jelajah Wisata Banjarnegara') ?>
        </h1>
        <p class="mt-6 text-xl text-teal-100 max-w-3xl">
            <?= trans('Official e-ticketing platform for Banjarnegara tourism. Discover the beauty of Dieng Plateau, hidden waterfalls, and cultural events with Hollynice Bureau.', 'Platform e-ticketing resmi wisata Banjarnegara. Temukan keindahan Dataran Tinggi Dieng, air terjun tersembunyi, dan acara budaya bersama Biro Hollynice.') ?>
        </p>
        <div class="mt-10">
            <a href="#tours" class="inline-block bg-orange-500 border border-transparent rounded-md py-3 px-8 font-medium text-white hover:bg-orange-600 transition">
                <?= trans('Book Now', 'Pesan Sekarang') ?>
            </a>
        </div>
    </div>
</div>

<!-- Products Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" id="tours">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900"><?= trans('Popular Destinations', 'Destinasi Populer') ?></h2>
        <p class="mt-4 text-gray-500"><?= trans('Choose your best adventure.', 'Pilih petualangan terbaik Anda.') ?></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($products as $product): ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300 flex flex-col">
                <div class="h-48 overflow-hidden relative group">
                    <img src="img/<?= $product['image'] ?>" alt="<?= $product['name_en'] ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    <div class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-2 py-1 m-2 rounded uppercase">
                        <?= $product['type'] ?>
                    </div>
                </div>
                <div class="p-6 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <?= trans($product['name_en'], $product['name_id']) ?>
                        </h3>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                            <?= trans($product['description_en'], $product['description_id']) ?>
                        </p>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-2xl font-bold text-teal-600">
                            <?= formatRupiah($product['price']) ?>
                        </span>
                        <a href="index.php?page=product&id=<?= $product['id'] ?>" class="text-teal-600 hover:text-teal-800 font-semibold text-sm flex items-center">
                            <?= trans('Details', 'Detail') ?> <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Call to Action -->
<div class="bg-teal-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between">
        <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-900"><?= trans('Ready to go?', 'Siap berangkat?') ?></h2>
            <p class="text-gray-600"><?= trans('Get your tickets easily with QR Code access.', 'Dapatkan tiket dengan mudah menggunakan akses QR Code.') ?></p>
        </div>
        <div class="flex space-x-4">
            <i class="fas fa-qrcode text-6xl text-gray-300"></i>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
