<?php include 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl tracking-tight">
            <?= trans('Transportation Services', 'Jasa Transportasi') ?>
        </h1>
        <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            <?= trans('Find the best travel, rental, and pickup services for your trip.', 'Temukan layanan travel, rental, dan antar-jemput terbaik untuk perjalanan Anda.') ?>
        </p>
        
        <!-- Filter Tabs -->
        <div class="mt-8 flex justify-center space-x-2">
            <a href="index.php?page=transportation&type=all" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= (!isset($_GET['type']) || $_GET['type'] == 'all') ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                <?= trans('All', 'Semua') ?>
            </a>
            <a href="index.php?page=transportation&type=travel" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= (isset($_GET['type']) && $_GET['type'] == 'travel') ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Travel
            </a>
            <a href="index.php?page=transportation&type=rental" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= (isset($_GET['type']) && $_GET['type'] == 'rental') ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Rental
            </a>
            <a href="index.php?page=transportation&type=pickup" class="px-4 py-2 rounded-full text-sm font-medium transition-colors <?= (isset($_GET['type']) && $_GET['type'] == 'pickup') ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                Pickup/Ojek
            </a>
        </div>
    </div>

    <!-- List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        <?php foreach ($transportations as $item): ?>
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full border border-gray-100">
            <!-- Image -->
            <div class="h-56 bg-gray-200 relative overflow-hidden group">
                <?php if (!empty($item['image']) && file_exists('img/' . $item['image'])): ?>
                    <img src="img/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                        <i class="fas fa-bus text-5xl"></i>
                    </div>
                <?php endif; ?>
                <div class="absolute top-0 right-0 bg-teal-600 text-white px-3 py-1 rounded-bl-lg text-sm font-bold shadow-md uppercase">
                    <?= htmlspecialchars($item['type']) ?>
                </div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <h3 class="text-2xl font-bold text-gray-900 mb-2 leading-tight">
                    <?= htmlspecialchars($item['name']) ?>
                </h3>
                
                <p class="text-gray-600 mb-6 flex-1">
                    <?= htmlspecialchars($item['description']) ?>
                </p>

                <div class="space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-tag w-6 text-teal-500 text-center mr-2"></i>
                        <span class="font-medium"><?= htmlspecialchars($item['price_range']) ?></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-phone w-6 text-teal-500 text-center mr-2"></i>
                        <span class="font-medium"><?= htmlspecialchars($item['contact_number']) ?></span>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 flex space-x-2">
                    <a href="index.php?page=transportation_detail&id=<?= $item['id'] ?>" class="flex-1 text-center bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition duration-300 font-medium">
                        Detail
                    </a>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $item['contact_number']) ?>" target="_blank" class="flex-1 text-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300 font-medium">
                        <i class="fab fa-whatsapp mr-1"></i> Pesan
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (empty($transportations)): ?>
    <div class="text-center py-20 bg-gray-50 rounded-xl">
        <i class="fas fa-bus text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-medium text-gray-900">Belum ada data transportasi</h3>
        <p class="mt-2 text-gray-500">Data transportasi akan segera hadir.</p>
    </div>
    <?php endif; ?>

</div>

<?php include 'views/layouts/footer.php'; ?>
