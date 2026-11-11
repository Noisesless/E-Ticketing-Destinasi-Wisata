<?php
// Mobile View doesn't need the standard header, maybe just a back button
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'id' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= trans($product['name_en'], $product['name_id']) ?></title>
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 pb-24">

    <!-- Back Button & Image Gallery -->
    <div class="relative h-72 group">
        <div class="w-full h-full flex overflow-x-auto snap-x snap-mandatory" style="scrollbar-width: none; -ms-overflow-style: none;">
            <!-- Main Image -->
            <div class="w-full flex-shrink-0 snap-center relative">
                <img src="img/<?= $product['image'] ?>" class="w-full h-full object-cover">
            </div>
            <!-- Gallery Images -->
            <?php foreach($gallery as $img): ?>
            <div class="w-full flex-shrink-0 snap-center relative">
                <img src="img/<?= $img['image_path'] ?>" class="w-full h-full object-cover">
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Overlay Controls -->
        <div class="absolute top-0 left-0 w-full p-4 flex justify-between items-center bg-gradient-to-b from-black/50 to-transparent pointer-events-none">
            <a href="javascript:history.back()" class="text-white text-xl pointer-events-auto">
                <i class="fas fa-arrow-left"></i>
            </a>
            <a href="index.php?page=cart" class="text-white text-xl relative pointer-events-auto">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
        
        <!-- Swipe Hint -->
        <?php if(!empty($gallery)): ?>
        <div class="absolute bottom-12 right-4 text-white text-xs bg-black/30 px-2 py-1 rounded backdrop-blur-sm pointer-events-none">
            <i class="fas fa-images mr-1"></i> <?= count($gallery) + 1 ?> Photos
        </div>
        <?php endif; ?>

        <div class="absolute bottom-4 left-4 text-white pointer-events-none">
             <div class="inline-block px-2 py-1 bg-black/50 rounded-md text-xs backdrop-blur-sm">
                <i class="far fa-clock mr-1"></i> <?= $product['duration'] ?: '1 Day' ?>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="relative -mt-6 bg-gray-100 rounded-t-3xl px-6 pt-8">
        <div class="flex justify-between items-start">
            <h1 class="text-2xl font-bold text-gray-800 w-2/3 leading-tight"><?= trans($product['name_en'], $product['name_id']) ?></h1>
            <div class="text-right">
                <span class="text-xl font-bold text-teal-600 block"><?= formatRupiah($product['price']) ?></span>
                <span class="text-xs text-gray-400">/ pax</span>
            </div>
        </div>
        
        <div class="mt-2 flex items-center space-x-2 text-sm text-gray-500">
            <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-md text-xs font-semibold"><?= strtoupper($product['type']) ?></span>
            <span><i class="fas fa-star text-yellow-400"></i> <?= $product['rating'] ?? '5.0' ?> (<?= $product['review_count'] ?? '0' ?>)</span>
        </div>

        <!-- Tabs Navigation (Simple) -->
        <div class="mt-6 flex overflow-x-auto space-x-4 border-b border-gray-200 pb-2 hide-scrollbar">
            <a href="#desc" class="text-teal-600 font-bold whitespace-nowrap">Overview</a>
            <?php if($product['itinerary_en'] || $product['itinerary_id']): ?>
            <a href="#itinerary" class="text-gray-500 font-medium whitespace-nowrap">Itinerary</a>
            <?php endif; ?>
             <?php if($product['facilities_en'] || $product['facilities_id']): ?>
            <a href="#facilities" class="text-gray-500 font-medium whitespace-nowrap">Facilities</a>
            <?php endif; ?>
             <?php if($product['policy_en'] || $product['policy_id']): ?>
            <a href="#policy" class="text-gray-500 font-medium whitespace-nowrap">Policy</a>
            <?php endif; ?>
        </div>

        <!-- Sections -->
        <div id="desc" class="mt-6">
            <h2 class="font-bold text-gray-800 mb-2"><?= trans('Destination Profile', 'Profil Destinasi') ?></h2>
            <p class="text-gray-600 text-sm leading-relaxed">
                <?= nl2br(trans($product['description_en'], $product['description_id'])) ?>
            </p>
        </div>

        <?php if($product['itinerary_en'] || $product['itinerary_id']): ?>
        <div id="itinerary" class="mt-8 pt-6 border-t border-gray-200">
            <h2 class="font-bold text-gray-800 mb-4"><?= trans('Itinerary', 'Rencana Perjalanan') ?></h2>
            <div class="space-y-4">
                <?php 
                $itinerary = trans($product['itinerary_en'], $product['itinerary_id']);
                $days = explode("\n", $itinerary);
                foreach ($days as $day): 
                    if(trim($day) == '') continue;
                ?>
                <div class="flex">
                    <div class="flex-shrink-0 mr-3">
                        <div class="h-6 w-6 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold text-xs">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded p-3 w-full shadow-sm">
                        <p class="text-gray-700 text-sm"><?= htmlspecialchars($day) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if($product['facilities_en'] || $product['facilities_id']): ?>
        <div id="facilities" class="mt-8 pt-6 border-t border-gray-200">
             <h2 class="font-bold text-gray-800 mb-4"><?= trans('Facilities', 'Fasilitas') ?></h2>
             <div class="bg-green-50 p-4 rounded-xl border border-green-100 text-sm text-green-800 whitespace-pre-line">
                 <?= trans($product['facilities_en'], $product['facilities_id']) ?>
             </div>
        </div>
        <?php endif; ?>

        <?php if($product['latitude'] && $product['longitude']): ?>
        <div class="mt-8 pt-6 border-t border-gray-200">
             <h2 class="font-bold text-gray-800 mb-4"><?= trans('Location', 'Lokasi') ?></h2>
             <div class="rounded-xl overflow-hidden shadow-sm h-64 w-full relative z-0">
                <iframe 
                    width="100%" 
                    height="100%" 
                    frameborder="0" 
                    scrolling="no" 
                    marginheight="0" 
                    marginwidth="0" 
                    src="https://maps.google.com/maps?q=<?= $product['latitude'] ?>,<?= $product['longitude'] ?>&hl=<?= $_SESSION['lang'] ?? 'id' ?>&z=15&output=embed">
                </iframe>
             </div>
             <a href="https://www.google.com/maps/search/?api=1&query=<?= $product['latitude'] ?>,<?= $product['longitude'] ?>" target="_blank" class="block mt-3 text-center w-full py-2 bg-white border border-gray-200 rounded-lg text-teal-600 font-semibold text-sm hover:bg-gray-50">
                <i class="fas fa-external-link-alt mr-2"></i> <?= trans('Open in Google Maps', 'Buka di Google Maps') ?>
             </a>
        </div>
        <?php endif; ?>

        <?php if($product['policy_en'] || $product['policy_id']): ?>
        <div id="policy" class="mt-8 pt-6 border-t border-gray-200 pb-20">
             <h2 class="font-bold text-gray-800 mb-4"><?= trans('Policies', 'Kebijakan') ?></h2>
             <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100 text-sm text-yellow-800 whitespace-pre-line">
                 <?= trans($product['policy_en'], $product['policy_id']) ?>
             </div>
        </div>
        <?php endif; ?>

        <!-- Booking Form -->
        <form method="POST" class="mt-8 pb-32">
            <div class="bg-white p-4 rounded-xl shadow-sm space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase"><?= trans('Visit Date', 'Tanggal Kunjungan') ?></label>
                    <input type="date" name="visit_date" required class="w-full mt-1 border-b border-gray-200 py-2 focus:outline-none focus:border-teal-500 text-sm">
                </div>
                <div class="flex justify-between items-center">
                    <label class="block text-xs font-bold text-gray-500 uppercase"><?= trans('Guests', 'Jumlah Tamu') ?></label>
                    <div class="flex items-center space-x-4">
                        <button type="button" onclick="document.getElementById('qty').stepDown()" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center">-</button>
                        <input type="number" id="qty" name="quantity" value="1" min="1" class="w-12 text-center bg-transparent font-bold">
                        <button type="button" onclick="document.getElementById('qty').stepUp()" class="w-8 h-8 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center">+</button>
                    </div>
                </div>
            </div>

            <!-- Fixed Bottom Button -->
            <div class="fixed bottom-0 left-0 w-full bg-white p-4 shadow-lg border-t border-gray-100 z-50 flex space-x-3">
                 <a href="https://wa.me/6281234567890?text=Halo%20Hollynice,%20saya%20tanya%20paket%20<?= urlencode($product['name_en']) ?>" target="_blank" class="flex-1 flex justify-center items-center bg-white border border-gray-300 text-gray-700 font-bold rounded-xl">
                    <i class="fab fa-whatsapp text-green-500 text-xl"></i>
                </a>
                <button type="submit" name="add_to_cart" class="flex-[4] bg-teal-600 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-teal-700 transition transform active:scale-95">
                    <?= trans('Book Now', 'Pesan Sekarang') ?>
                </button>
            </div>
        </form>
    </div>

</body>
</html>
