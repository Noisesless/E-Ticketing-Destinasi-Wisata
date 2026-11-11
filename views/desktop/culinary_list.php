<?php include 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl tracking-tight">
            Kuliner Legendaris Banjarnegara
        </h1>
        <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            Jelajahi cita rasa otentik yang telah melegenda. Dari Dawet Ayu yang segar hingga Mie Ongklok yang menghangatkan.
        </p>
    </div>

    <!-- List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <?php foreach ($culinary_spots as $spot): ?>
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full border border-gray-100">
            <!-- Image -->
            <div class="h-56 bg-gray-200 relative overflow-hidden group">
                <?php if (!empty($spot['image']) && file_exists('img/' . $spot['image'])): ?>
                    <img src="img/<?= htmlspecialchars($spot['image']) ?>" alt="<?= htmlspecialchars($spot['name']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                        <i class="fas fa-utensils text-5xl"></i>
                    </div>
                <?php endif; ?>
                <div class="absolute top-0 right-0 bg-orange-500 text-white px-3 py-1 rounded-bl-lg text-sm font-bold shadow-md">
                    <i class="fas fa-star text-yellow-300 mr-1"></i> <?= $spot['rating'] ?>
                </div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <h3 class="text-2xl font-bold text-gray-900 mb-2 leading-tight">
                    <?= htmlspecialchars($spot['name']) ?>
                </h3>
                
                <div class="flex items-start text-sm text-gray-500 mb-4">
                    <i class="fas fa-map-marker-alt mt-1 mr-2 text-red-500 flex-shrink-0"></i>
                    <span class="line-clamp-1"><?= htmlspecialchars($spot['location']) ?></span>
                </div>
                
                <p class="text-gray-600 mb-6 line-clamp-3 flex-1">
                    <?= htmlspecialchars($spot['description']) ?>
                </p>

                <div class="bg-orange-50 rounded-lg p-4 mb-6 border border-orange-100">
                    <div class="text-xs text-orange-600 font-bold uppercase tracking-wider mb-1">Menu Andalan</div>
                    <div class="text-gray-900 font-medium">
                        <i class="fas fa-fire text-orange-500 mr-1"></i> <?= htmlspecialchars($spot['signature_dish']) ?>
                    </div>
                </div>
                
                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-500">Kisaran Harga</div>
                        <div class="text-sm font-bold text-gray-900"><?= htmlspecialchars($spot['price_range']) ?></div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Buka</div>
                        <div class="text-sm font-bold text-green-600"><?= htmlspecialchars($spot['opening_hours']) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($culinary_spots)): ?>
    <div class="text-center py-20 bg-gray-50 rounded-xl">
        <i class="fas fa-utensils text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-medium text-gray-900">Belum ada data kuliner</h3>
        <p class="mt-2 text-gray-500">Data kuliner akan segera hadir.</p>
    </div>
    <?php endif; ?>

</div>

<?php include 'views/layouts/footer.php'; ?>
