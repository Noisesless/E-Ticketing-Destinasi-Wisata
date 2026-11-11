<?php include 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="index.php" class="text-gray-500 hover:text-teal-600">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="index.php?page=transportation" class="text-gray-500 hover:text-teal-600">Transportasi</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-900 font-medium"><?= htmlspecialchars($transportation['name']) ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Left Column: Image & Main Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Main Image -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="aspect-w-16 aspect-h-9 relative">
                    <?php if (!empty($transportation['image']) && file_exists('img/' . $transportation['image'])): ?>
                        <img src="img/<?= htmlspecialchars($transportation['image']) ?>" alt="<?= htmlspecialchars($transportation['name']) ?>" class="w-full h-[400px] object-cover">
                    <?php else: ?>
                        <div class="w-full h-[400px] bg-gray-100 flex items-center justify-center text-gray-400">
                            <i class="fas fa-bus text-6xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-4 right-4 bg-teal-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg uppercase tracking-wide">
                        <?= htmlspecialchars($transportation['type']) ?>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi Layanan</h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    <?= nl2br(htmlspecialchars($transportation['description'])) ?>
                </div>
            </div>

            <!-- Facilities -->
            <?php if (!empty($transportation['facilities'])): ?>
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Fasilitas Armada</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php 
                    $facilities = explode(',', $transportation['facilities']);
                    foreach ($facilities as $facility): 
                        $facility = trim($facility);
                        if(empty($facility)) continue;
                    ?>
                    <div class="flex items-center bg-gray-50 p-4 rounded-lg">
                        <div class="bg-teal-100 text-teal-600 rounded-full p-2 mr-3">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span class="text-gray-700 font-medium"><?= htmlspecialchars($facility) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Booking Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl p-8 sticky top-24 border border-gray-100">
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2"><?= htmlspecialchars($transportation['name']) ?></h1>
                <p class="text-sm text-gray-500 mb-6">ID: TR-<?= str_pad($transportation['id'], 3, '0', STR_PAD_LEFT) ?></p>

                <div class="space-y-6 mb-8">
                    <!-- Price -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <span class="text-gray-600 font-medium">Harga Mulai</span>
                        <span class="text-xl font-bold text-teal-600"><?= htmlspecialchars($transportation['price_range']) ?></span>
                    </div>

                    <!-- Capacity -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Kapasitas</p>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($transportation['capacity']) ?> Penumpang</p>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 mr-4">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Kontak</p>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($transportation['contact_number']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Booking Actions -->
                <div class="space-y-4">
                    <a href="<?= $wa_link ?>" target="_blank" class="block w-full bg-green-500 text-white text-center py-4 rounded-xl hover:bg-green-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-bold text-lg flex items-center justify-center">
                        <i class="fab fa-whatsapp text-2xl mr-2"></i> Pesan Sekarang
                    </a>
                    <p class="text-xs text-center text-gray-500">
                        Anda akan dialihkan ke WhatsApp untuk terhubung langsung dengan penyedia layanan.
                    </p>
                </div>

                <!-- Safety Note -->
                <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-yellow-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Tips Keamanan</h3>
                            <div class="mt-2 text-xs text-yellow-700">
                                <p>Pastikan untuk menyepakati harga dan titik penjemputan dengan jelas sebelum melakukan pembayaran.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
