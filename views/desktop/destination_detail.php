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
                    <a href="index.php?page=map" class="text-sm font-medium text-gray-700 hover:text-blue-600">Peta Destinasi</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500"><?= htmlspecialchars($destination['name']) ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Image Section -->
            <div class="h-96 md:h-full relative bg-gray-200">
                <img src="<?= htmlspecialchars($destination['image']) ?>" alt="<?= htmlspecialchars($destination['name']) ?>" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wide shadow-md">
                    Wisata
                </div>
            </div>

            <!-- Details Section -->
            <div class="p-8 md:p-12 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-3xl font-extrabold text-gray-900">
                            <?= htmlspecialchars($destination['name']) ?>
                        </h1>
                        <div class="flex items-center bg-green-100 px-3 py-1 rounded-lg">
                            <span class="text-sm font-bold text-green-800"><?= htmlspecialchars($destination['type']) ?></span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-500 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500 text-lg"></i>
                        <?= htmlspecialchars($destination['location']) ?>
                    </p>

                    <div class="prose max-w-none text-gray-600 mb-8">
                        <p><?= nl2br(htmlspecialchars($destination['description'])) ?></p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <span class="block text-xs font-bold text-gray-500 uppercase">Jam Buka</span>
                            <span class="block text-lg font-semibold text-gray-900"><?= htmlspecialchars($destination['opening_hours']) ?></span>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <span class="block text-xs font-bold text-gray-500 uppercase">Tiket Masuk</span>
                            <span class="block text-lg font-semibold text-gray-900"><?= htmlspecialchars($destination['ticket_price']) ?></span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8 mt-4">
                    <div class="flex items-end justify-between">
                        <div>
                           <!-- Empty for balance -->
                        </div>
                        <div class="flex space-x-3">
                             <a href="https://maps.google.com/?q=<?= urlencode($destination['name'] . ' ' . $destination['location']) ?>" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-map-marked-alt mr-2"></i>
                                Petunjuk Arah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
