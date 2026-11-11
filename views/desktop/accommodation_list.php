<?php include 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
            Penginapan & Homestay
        </h1>
        <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            Temukan tempat istirahat terbaik di sekitar destinasi wisata kami. Mulai dari hotel berbintang hingga homestay yang nyaman.
        </p>
    </div>

    <!-- Filters & Sort -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex space-x-2 mb-4 md:mb-0">
            <a href="index.php?page=accommodation&type=all" class="px-4 py-2 rounded-full text-sm font-medium <?= $type_filter == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                Semua
            </a>
            <a href="index.php?page=accommodation&type=hotel" class="px-4 py-2 rounded-full text-sm font-medium <?= $type_filter == 'hotel' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                Hotel
            </a>
            <a href="index.php?page=accommodation&type=homestay" class="px-4 py-2 rounded-full text-sm font-medium <?= $type_filter == 'homestay' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                Homestay
            </a>
            <a href="index.php?page=accommodation&type=villa" class="px-4 py-2 rounded-full text-sm font-medium <?= $type_filter == 'villa' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' ?>">
                Villa
            </a>
        </div>
        
        <div class="flex items-center">
            <label for="sort" class="mr-2 text-sm text-gray-600">Urutkan:</label>
            <select onchange="location = this.value;" class="form-select block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="index.php?page=accommodation&type=<?= $type_filter ?>&sort=newest" <?= $sort_by == 'newest' ? 'selected' : '' ?>>Terbaru</option>
                <option value="index.php?page=accommodation&type=<?= $type_filter ?>&sort=price_low" <?= $sort_by == 'price_low' ? 'selected' : '' ?>>Harga Terendah</option>
                <option value="index.php?page=accommodation&type=<?= $type_filter ?>&sort=price_high" <?= $sort_by == 'price_high' ? 'selected' : '' ?>>Harga Tertinggi</option>
                <option value="index.php?page=accommodation&type=<?= $type_filter ?>&sort=rating" <?= $sort_by == 'rating' ? 'selected' : '' ?>>Rating Tertinggi</option>
            </select>
        </div>
    </div>

    <!-- List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($accommodations as $item): ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full">
            <!-- Image -->
            <a href="index.php?page=accommodation_detail&id=<?= $item['id'] ?>" class="h-48 bg-gray-200 relative block overflow-hidden">
                <?php if (!empty($item['image']) && file_exists('img/' . $item['image'])): ?>
                    <img src="img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <i class="fas fa-bed text-4xl"></i>
                    </div>
                <?php endif; ?>
                <div class="absolute top-4 right-4 bg-white px-2 py-1 rounded-md text-xs font-bold uppercase tracking-wide text-gray-800 shadow">
                    <?= htmlspecialchars($item['type']) ?>
                </div>
            </a>
            
            <div class="p-6 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-gray-900">
                        <?= htmlspecialchars($item['name']) ?>
                    </h3>
                    <div class="flex items-center bg-yellow-100 px-2 py-1 rounded">
                        <i class="fas fa-star text-yellow-500 text-sm mr-1"></i>
                        <span class="text-sm font-semibold text-gray-800"><?= $item['rating'] ?></span>
                    </div>
                </div>
                
                <p class="text-sm text-gray-500 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                    <?= htmlspecialchars($item['location']) ?>
                </p>
                
                <p class="text-gray-600 mb-4 text-sm line-clamp-3 flex-1">
                    <?= htmlspecialchars($item['description']) ?>
                </p>
                
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php 
                        $facilities = explode(',', $item['facilities']);
                        foreach($facilities as $fac): 
                        ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= trim($fac) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="flex items-center justify-between mt-auto">
                        <div>
                            <span class="text-xs text-gray-500">Mulai dari</span>
                            <div class="text-lg font-bold text-blue-600">
                                <?= formatRupiah($item['price_per_night']) ?>
                                <span class="text-xs font-normal text-gray-500">/malam</span>
                            </div>
                        </div>
                        <a href="index.php?page=accommodation_detail&id=<?= $item['id'] ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($accommodations)): ?>
    <div class="text-center py-12">
        <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900">Tidak ada penginapan ditemukan</h3>
        <p class="mt-1 text-gray-500">Coba ubah filter pencarian Anda.</p>
    </div>
    <?php endif; ?>

</div>

<?php include 'views/layouts/footer.php'; ?>
