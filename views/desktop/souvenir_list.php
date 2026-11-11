<?php include 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl tracking-tight">
            <?= trans('Banjarnegara Souvenirs & Local Crafts', 'Oleh-oleh & Cindera Mata Banjarnegara') ?>
        </h1>
        <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            <?= trans('Bring home authentic memories, local handicrafts, and delicacies from Banjarnegara.', 'Bawa pulang kenangan manis, kerajinan tangan, dan produk khas terbaik dari Banjarnegara.') ?>
        </p>

        <!-- Filter & Sorting Bar -->
        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100 max-w-4xl mx-auto">
            <div class="text-sm font-semibold text-gray-600 mb-3 sm:mb-0 flex items-center">
                <i class="fas fa-gift text-teal-600 mr-2 text-lg"></i>
                <span><?= count($souvenirs) ?> <?= trans('Products Available', 'Produk Tersedia') ?></span>
            </div>
            <div class="flex items-center space-x-2">
                <label for="sort" class="text-sm font-medium text-gray-600 whitespace-nowrap"><?= trans('Sort by:', 'Urutkan:') ?></label>
                <select id="sort" onchange="location = this.value;" class="block w-full sm:w-auto pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-teal-500 focus:border-teal-500 rounded-lg border bg-gray-50 text-gray-700">
                    <option value="index.php?page=souvenir&sort=newest" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'newest') ? 'selected' : '' ?>><?= trans('Newest', 'Terbaru') ?></option>
                    <option value="index.php?page=souvenir&sort=price_low" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_low') ? 'selected' : '' ?>><?= trans('Price: Low to High', 'Harga Terendah') ?></option>
                    <option value="index.php?page=souvenir&sort=price_high" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_high') ? 'selected' : '' ?>><?= trans('Price: High to Low', 'Harga Tertinggi') ?></option>
                </select>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <?php if (!empty($souvenirs)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <?php foreach ($souvenirs as $item): ?>
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full border border-gray-100">
            <!-- Image -->
            <div class="h-60 bg-gray-100 relative overflow-hidden group">
                <?php if (!empty($item['image']) && file_exists('img/' . $item['image'])): ?>
                    <img src="img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-400 bg-gray-50">
                        <i class="fas fa-gift text-5xl"></i>
                    </div>
                <?php endif; ?>
                <div class="absolute top-0 right-0 bg-teal-600 text-white px-3 py-1 rounded-bl-lg text-xs font-bold uppercase tracking-wider shadow-md">
                    <?= trans('Authentic', 'Khas Asli') ?>
                </div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <h3 class="text-2xl font-bold text-gray-900 mb-2 leading-tight">
                    <?= htmlspecialchars($item['name']) ?>
                </h3>
                
                <p class="text-gray-600 mb-6 flex-1 line-clamp-3">
                    <?= htmlspecialchars($item['description']) ?>
                </p>

                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold"><?= trans('Price', 'Harga') ?></div>
                        <div class="text-xl font-extrabold text-teal-600">
                            <?= is_numeric($item['price']) ? formatRupiah($item['price']) : htmlspecialchars($item['price']) ?>
                        </div>
                    </div>
                    <div>
                        <a href="https://wa.me/<?= $wa_number ?>?text=<?= urlencode('Halo Admin Hollynice, saya tertarik untuk memesan produk oleh-oleh: ' . $item['name']) ?>" 
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                            <i class="fab fa-whatsapp mr-2 text-base"></i> <?= trans('Order via WhatsApp', 'Pesan via WA') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100 max-w-xl mx-auto">
        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
        <p class="text-gray-500 text-lg font-medium"><?= trans('No souvenirs available yet.', 'Belum ada produk oleh-oleh yang tersedia saat ini.') ?></p>
    </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>

