<?php include 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="index.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="index.php?page=accommodation" class="text-sm font-medium text-gray-700 hover:text-blue-600">Penginapan</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500"><?= htmlspecialchars($accommodation['name']) ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Image Section -->
            <div class="h-96 md:h-full relative bg-gray-200">
                <?php if (!empty($accommodation['image']) && file_exists('img/' . $accommodation['image'])): ?>
                    <img src="img/<?= htmlspecialchars($accommodation['image']) ?>" alt="<?= htmlspecialchars($accommodation['name']) ?>" class="absolute inset-0 w-full h-full object-cover">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-400">
                        <i class="fas fa-image text-6xl"></i>
                    </div>
                <?php endif; ?>
                <div class="absolute top-4 left-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wide shadow-md">
                    <?= htmlspecialchars($accommodation['type']) ?>
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-8 md:p-12 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-3xl font-extrabold text-gray-900">
                            <?= htmlspecialchars($accommodation['name']) ?>
                        </h1>
                        <div class="flex items-center bg-yellow-100 px-3 py-1 rounded-lg">
                            <i class="fas fa-star text-yellow-500 text-lg mr-1"></i>
                            <span class="text-lg font-bold text-gray-800"><?= $accommodation['rating'] ?></span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500 text-lg"></i>
                        <?= htmlspecialchars($accommodation['location']) ?>
                    </p>

                    <div class="prose max-w-none text-gray-600 mb-8">
                        <p><?= nl2br(htmlspecialchars($accommodation['description'])) ?></p>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Fasilitas</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php 
                            $facilities = explode(',', $accommodation['facilities']);
                            foreach($facilities as $fac): 
                            ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-50 text-teal-700 border border-teal-100">
                                    <i class="fas fa-check mr-2 text-xs"></i>
                                    <?= trim($fac) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8 mt-4">
                    <div class="flex items-end justify-between">
                        <div>
                            <span class="text-sm text-gray-500">Harga mulai dari</span>
                            <div class="text-3xl font-bold text-blue-600">
                                <?= formatRupiah($accommodation['price_per_night']) ?>
                                <span class="text-base font-normal text-gray-500">/malam</span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="https://wa.me/?text=Saya%20tertarik%20booking%20<?= urlencode($accommodation['name']) ?>" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fab fa-whatsapp mr-2"></i>
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Accommodations (Optional, maybe later) -->
</div>

<?php include 'views/layouts/footer.php'; ?>
