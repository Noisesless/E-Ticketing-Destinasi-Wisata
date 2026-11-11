<?php require 'views/layouts/header.php'; ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRS9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


<div class="bg-gray-50 min-h-screen pb-12">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            
            <!-- Main Content (Left Column) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="aspect-w-16 aspect-h-9 relative">
                        <img id="mainImage" src="img/<?= $product['image'] ?>" alt="<?= $product['name_en'] ?>" class="w-full h-full object-cover transition-opacity duration-300">
                    </div>
                    <?php if (!empty($gallery)): ?>
                    <div class="flex p-4 space-x-4 overflow-x-auto bg-gray-50">
                        <!-- Main Image Thumb -->
                        <button onclick="changeImage('img/<?= $product['image'] ?>')" class="flex-shrink-0 w-24 h-16 rounded overflow-hidden border-2 border-transparent hover:border-teal-500 focus:outline-none focus:border-teal-500 transition cursor-pointer">
                            <img src="img/<?= $product['image'] ?>" class="w-full h-full object-cover">
                        </button>
                        <!-- Gallery Thumbs -->
                        <?php foreach($gallery as $img): ?>
                        <button onclick="changeImage('img/<?= $img['image_path'] ?>')" class="flex-shrink-0 w-24 h-16 rounded overflow-hidden border-2 border-transparent hover:border-teal-500 focus:outline-none focus:border-teal-500 transition cursor-pointer">
                            <img src="img/<?= $img['image_path'] ?>" class="w-full h-full object-cover">
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <script>
                function changeImage(src) {
                    const mainImage = document.getElementById('mainImage');
                    mainImage.style.opacity = '0';
                    setTimeout(() => {
                        mainImage.src = src;
                        mainImage.style.opacity = '1';
                    }, 200);
                }
                </script>

                <!-- YouTube Video Section (Optional) -->
                <?php if (!empty($product['youtube_url'])): 
                    $embedUrl = getYoutubeEmbedUrl($product['youtube_url']);
                    if ($embedUrl):
                ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden p-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Video Preview', 'Video Preview') ?>
                    </h2>
                    <div class="aspect-w-16 aspect-h-9 relative" style="padding-bottom: 56.25%;">
                        <iframe class="absolute top-0 left-0 w-full h-full rounded-lg" 
                                src="<?= $embedUrl ?>" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>
                <?php endif; endif; ?>

                <!-- Product Title & Stats -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900"><?= trans($product['name_en'], $product['name_id']) ?></h1>
                            <?php if(!empty($product['tagline_en'])): ?>
                                <p class="text-lg text-teal-600 font-medium mt-1 italic">"<?= trans($product['tagline_en'], $product['tagline_id']) ?>"</p>
                            <?php endif; ?>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-2 text-teal-500"></i>
                                <span>Banjarnegara, Indonesia</span>
                                <span class="mx-2">•</span>
                                <i class="far fa-clock mr-2 text-teal-500"></i>
                                <span>
                                    <?php if(!empty($product['event_date'])): ?>
                                        <?= date('d M Y', strtotime($product['event_date'])) ?>
                                        <?php if($product['event_end_date']): ?>
                                            - <?= date('d M Y', strtotime($product['event_end_date'])) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?= $product['duration'] ?: '1 Day' ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                             <div class="flex items-center">
                                <i class="fas fa-star text-yellow-400"></i>
                                <span class="ml-1 font-bold text-gray-900"><?= $product['rating'] ?? '5.0' ?></span>
                                <span class="ml-1 text-gray-500">(<?= $product['review_count'] ?? '0' ?> reviews)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description / Destination Profile -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Destination Profile', 'Profil Destinasi') ?>
                    </h2>
                    <div class="prose max-w-none text-gray-700">
                        <?= nl2br(trans($product['description_en'], $product['description_id'])) ?>
                    </div>
                </div>

                <!-- Operating Hours (Entrance Only) -->
                <?php if ($product['type'] === 'entrance' && ($product['opening_hours_en'] || $product['opening_hours_id'])): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Operating Hours', 'Jam Operasional') ?>
                    </h2>
                    <div class="flex items-start">
                        <i class="far fa-clock text-teal-500 mt-1 mr-3"></i>
                        <div class="text-gray-700 whitespace-pre-line">
                            <?= trans($product['opening_hours_en'], $product['opening_hours_id']) ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Ticket Prices (Entrance Only) -->
                <?php if ($product['type'] === 'entrance' && !empty($product['price_variants'])): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Ticket Prices', 'Harga Tiket') ?>
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= trans('Category', 'Kategori') ?></th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"><?= trans('Price', 'Harga') ?></th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= trans('Info', 'Info') ?></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php 
                                $variants = json_decode($product['price_variants'], true);
                                foreach ($variants as $variant): 
                                ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $variant['name'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-teal-600 font-bold"><?= formatRupiah($variant['price']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $variant['info'] ?? '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-sm text-gray-500 italic">
                        <i class="fas fa-info-circle mr-1"></i> <?= trans('Prices include tax. Insurance not included.', 'Harga sudah termasuk pajak. Belum termasuk asuransi.') ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Itinerary -->
                <?php if($product['itinerary_en'] || $product['itinerary_id']): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Itinerary', 'Rencana Perjalanan') ?>
                    </h2>
                    <div class="space-y-4">
                        <?php 
                        $itinerary = trans($product['itinerary_en'], $product['itinerary_id']);
                        $days = explode("\n", $itinerary); // Simple split by newline
                        foreach ($days as $day): 
                            if(trim($day) == '') continue;
                        ?>
                        <div class="flex">
                            <div class="flex-shrink-0 mr-4">
                                <div class="h-8 w-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold text-sm">
                                    <i class="fas fa-map-signs"></i>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded p-3 w-full border border-gray-100">
                                <p class="text-gray-700"><?= htmlspecialchars($day) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Facilities / Inclusions -->
                <?php if($product['type'] === 'entrance'): ?>
                    <?php if (($product['facilities_en'] || $product['facilities_id']) || ($product['exclusions_en'] || $product['exclusions_id'])): ?>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                            <?= trans('What is Included?', 'Apa yang Didapat?') ?>
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Inclusions -->
                            <?php if ($product['facilities_en'] || $product['facilities_id']): ?>
                            <div>
                                <h3 class="font-bold text-green-700 mb-3 flex items-center">
                                    <i class="fas fa-check-circle mr-2 text-green-500"></i> <?= trans('Included', 'Termasuk') ?>
                                </h3>
                                <div class="bg-green-50 p-4 rounded-lg border border-green-100 text-sm text-green-800 whitespace-pre-line">
                                    <?= trans($product['facilities_en'], $product['facilities_id']) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Exclusions -->
                            <?php if ($product['exclusions_en'] || $product['exclusions_id']): ?>
                            <div>
                                <h3 class="font-bold text-red-700 mb-3 flex items-center">
                                    <i class="fas fa-times-circle mr-2 text-red-500"></i> <?= trans('Not Included', 'Tidak Termasuk') ?>
                                </h3>
                                <div class="bg-red-50 p-4 rounded-lg border border-red-100 text-sm text-red-800 whitespace-pre-line">
                                    <?= trans($product['exclusions_en'], $product['exclusions_id']) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php elseif($product['facilities_en'] || $product['facilities_id']): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Facilities', 'Fasilitas') ?>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <h3 class="font-bold text-green-800 mb-2"><i class="fas fa-check-circle mr-2"></i> Included</h3>
                            <div class="text-sm text-green-700 whitespace-pre-line">
                                <?= trans($product['facilities_en'], $product['facilities_id']) ?>
                            </div>
                        </div>
                        <!-- We might need to split included/excluded later, for now assuming mixed or user formats it -->
                    </div>
                </div>
                <?php endif; ?>

                <!-- Accessibility -->
                <?php if(!empty($product['accessibility_en'])): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Accessibility', 'Aksesibilitas') ?>
                    </h2>
                    <div class="flex items-start">
                        <i class="fas fa-wheelchair text-teal-500 mt-1 mr-3"></i>
                        <p class="text-gray-700"><?= trans($product['accessibility_en'], $product['accessibility_id']) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <!-- What to Bring -->
                <?php if(!empty($product['what_to_bring_en'])): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('What to Bring', 'Yang Perlu Dibawa') ?>
                    </h2>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="flex">
                            <i class="fas fa-suitcase-rolling text-blue-500 mt-1 mr-3"></i>
                            <p class="text-blue-800 text-sm whitespace-pre-line"><?= trans($product['what_to_bring_en'], $product['what_to_bring_id']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                 <!-- Policy -->
                 <?php if($product['policy_en'] || $product['policy_id']): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Policies', 'Kebijakan & Aturan') ?>
                    </h2>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 text-sm text-yellow-800 whitespace-pre-line">
                        <?= trans($product['policy_en'], $product['policy_id']) ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Ticket Terms (Entrance Only) -->
                <?php if ($product['type'] === 'entrance' && ($product['terms_en'] || $product['terms_id'])): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Ticket Terms', 'Ketentuan Tiket') ?>
                    </h2>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-sm text-blue-800 whitespace-pre-line">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle mt-1 mr-3"></i>
                            <div>
                                <?= trans($product['terms_en'], $product['terms_id']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Location & Contact (Entrance Only) -->
                <?php if ($product['type'] === 'entrance' && ($product['address_en'] || $product['address_id'])): ?>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Location & Contact', 'Lokasi & Kontak') ?>
                    </h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-teal-500 mt-1 mr-3 w-5 text-center"></i>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm"><?= trans('Address', 'Alamat') ?></h4>
                                <p class="text-gray-600 text-sm"><?= trans($product['address_en'], $product['address_id']) ?></p>
                            </div>
                        </div>
                        
                        <?php if(!empty($product['contact_phone'])): ?>
                        <div class="flex items-start">
                            <i class="fas fa-phone text-teal-500 mt-1 mr-3 w-5 text-center"></i>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm"><?= trans('Phone / WhatsApp', 'Telepon / WhatsApp') ?></h4>
                                <p class="text-gray-600 text-sm"><?= $product['contact_phone'] ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if(!empty($product['contact_email'])): ?>
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-teal-500 mt-1 mr-3 w-5 text-center"></i>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">Email</h4>
                                <p class="text-gray-600 text-sm"><?= $product['contact_email'] ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Reviews (Mock) -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">
                        <?= trans('Reviews', 'Ulasan Pelanggan') ?>
                    </h2>
                    <div class="space-y-4">
                        <!-- Mock Review 1 -->
                        <div class="flex items-start">
                            <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Budi+Santoso&background=random" alt="">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Budi Santoso</p>
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Sangat memuaskan! Pemandangannya indah dan guide-nya ramah.</p>
                            </div>
                        </div>
                        <!-- Mock Review 2 -->
                         <div class="flex items-start">
                            <img class="h-10 w-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Sarah+J&background=random" alt="">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Sarah Jenkins</p>
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <i class="fas fa-star text-gray-300 text-xs"></i>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Great experience, but the road was a bit bumpy.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Booking Sidebar (Right Column) -->
            <div class="mt-8 lg:mt-0 lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6 border border-gray-200">
                    
                    <?php if(!empty($product['event_date']) && strtotime($product['event_date']) > time()): ?>
                    <div class="bg-teal-600 text-white p-4 rounded-t-lg -mt-6 -mx-6 mb-6 text-center">
                        <p class="text-xs uppercase tracking-wider opacity-90 mb-1"><?= trans('Event Starts In', 'Event Dimulai Dalam') ?></p>
                        <div class="text-3xl font-bold" id="countdown">--d --h --m</div>
                    </div>
                    <script>
                        (function() {
                            const eventDate = new Date("<?= $product['event_date'] ?>").getTime();
                            const timer = setInterval(function() {
                                const now = new Date().getTime();
                                const distance = eventDate - now;
                                if (distance < 0) {
                                    clearInterval(timer);
                                    document.getElementById("countdown").innerHTML = "STARTED";
                                    return;
                                }
                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                document.getElementById("countdown").innerHTML = days + "d " + hours + "h " + minutes + "m";
                            }, 1000);
                        })();
                    </script>
                    <?php else: ?>
                    <h2 class="text-lg font-bold text-gray-900 mb-4"><?= trans('Booking Info', 'Info Pemesanan') ?></h2>
                    <?php endif; ?>
                    
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-1"><?= trans('Price', 'Harga') ?></p>
                        <?php 
                        $variants = !empty($product['price_variants']) ? json_decode($product['price_variants'], true) : null;
                        $initialPrice = $variants ? $variants[0]['price'] : $product['price'];
                        ?>
                        <p class="text-4xl font-bold text-teal-600" id="display_price"><?= formatRupiah($initialPrice) ?></p>
                        <p class="text-xs text-gray-400 mt-1">/ person (pax)</p>
                    </div>

                    <form method="POST" class="space-y-4">
                        <?php if($variants): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1"><?= trans('Ticket Category', 'Kategori Tiket') ?></label>
                            <select name="variant_idx" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2" onchange="updatePrice(this)">
                                <?php foreach($variants as $idx => $v): ?>
                                <option value="<?= $idx ?>" data-price="<?= $v['price'] ?>" data-info="<?= htmlspecialchars($v['info'] ?? '') ?>">
                                    <?= $v['name'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <p id="variant_info" class="text-xs text-gray-500 mt-1"><?= $variants[0]['info'] ?? '' ?></p>
                        </div>
                        <script>
                            function updatePrice(el) {
                                const opt = el.options[el.selectedIndex];
                                const price = opt.getAttribute('data-price');
                                const info = opt.getAttribute('data-info');
                                document.getElementById('display_price').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
                                document.getElementById('variant_info').innerText = info;
                            }
                        </script>
                        <?php endif; ?>

                        <?php if($product['event_date']): ?>
                            <!-- For events, date is fixed -->
                            <input type="hidden" name="visit_date" value="<?= date('Y-m-d', strtotime($product['event_date'])) ?>">
                            <div class="bg-gray-50 p-3 rounded border border-gray-200 text-sm">
                                <span class="block font-bold text-gray-700"><?= trans('Date', 'Tanggal') ?>:</span>
                                <?= date('d M Y', strtotime($product['event_date'])) ?>
                            </div>
                        <?php else: ?>
                        <div>
                            <label for="visit_date" class="block text-sm font-medium text-gray-700"><?= trans('Travel Date', 'Tanggal Keberangkatan') ?></label>
                            <input type="date" id="visit_date" name="visit_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                        </div>
                        <?php endif; ?>

                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700"><?= trans('Guests', 'Jumlah Peserta') ?></label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="number" id="quantity" name="quantity" min="1" value="1" required class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    Pax
                                </span>
                            </div>
                            <?php if(!empty($product['quota'])): ?>
                            <p class="text-xs text-red-500 mt-1 text-right">Quota Left: <?= $product['quota'] ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="pt-4">
                            <button type="submit" name="add_to_cart" class="w-full bg-teal-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all transform hover:scale-105">
                                <?= trans('Book Now', 'Pesan Sekarang') ?>
                            </button>
                        </div>
                         
                         <div class="mt-4">
                             <a href="https://wa.me/6281234567890?text=Halo%20Hollynice,%20saya%20tanya%20paket%20<?= urlencode($product['name_en']) ?>" target="_blank" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fab fa-whatsapp text-green-500 mr-2"></i> Chat WhatsApp
                            </a>
                         </div>
                    </form>

                    <!-- Trust Badges -->
                    <div class="mt-8 pt-6 border-t border-gray-200 space-y-3">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-check-circle text-teal-500 w-5"></i>
                            <span class="ml-2">Official Licensed (SIUPL)</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-shield-alt text-teal-500 w-5"></i>
                            <span class="ml-2">Secure Payment (QRIS/VA)</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-medal text-teal-500 w-5"></i>
                            <span class="ml-2">Best Price Guarantee</span>
                        </div>
                    </div>

                    <!-- Location Preview -->
                    <?php if($product['latitude'] && $product['longitude']): ?>
                    <div class="mt-6 pt-6 border-t border-gray-200">
                         <h3 class="text-sm font-bold text-gray-900 mb-2"><?= trans('Location', 'Lokasi') ?></h3>
                         
                         <!-- Google Maps Iframe -->
                         <div class="rounded-md overflow-hidden border border-gray-200 shadow-sm">
                            <iframe 
                                width="100%" 
                                height="250" 
                                frameborder="0" 
                                scrolling="no" 
                                marginheight="0" 
                                marginwidth="0" 
                                src="https://maps.google.com/maps?q=<?= $product['latitude'] ?>,<?= $product['longitude'] ?>&hl=id&z=14&output=embed">
                            </iframe>
                         </div>

                         <?php if(!empty($product['meeting_point_en'])): ?>
                         <div class="mt-3 flex items-start text-sm text-gray-600 bg-gray-50 p-2 rounded">
                            <i class="fas fa-map-pin text-red-500 mt-1 mr-2 flex-shrink-0"></i>
                            <div>
                                <span class="font-semibold block"><?= trans('Meeting Point', 'Titik Kumpul') ?>:</span>
                                <?= trans($product['meeting_point_en'], $product['meeting_point_id']) ?>
                            </div>
                         </div>
                         <?php endif; ?>
                         
                         <div class="mt-2 text-right">
                             <a href="https://www.google.com/maps/search/?api=1&query=<?= $product['latitude'] ?>,<?= $product['longitude'] ?>" target="_blank" class="text-xs text-teal-600 hover:text-teal-800 font-medium">
                                 <i class="fas fa-external-link-alt mr-1"></i> <?= trans('Open in Google Maps', 'Buka di Google Maps') ?>
                             </a>
                         </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Social Share -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-bold text-gray-900 mb-2"><?= trans('Share This', 'Bagikan') ?></h3>
                        <div class="flex space-x-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="flex-1 bg-blue-600 text-white text-center py-2 rounded text-sm hover:bg-blue-700 transition">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($product['name_en']) ?>&url=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="flex-1 bg-blue-400 text-white text-center py-2 rounded text-sm hover:bg-blue-500 transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text=<?= urlencode($product['name_en'] . " - http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="flex-1 bg-green-500 text-white text-center py-2 rounded text-sm hover:bg-green-600 transition">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied!');" class="flex-1 bg-gray-200 text-gray-600 text-center py-2 rounded text-sm hover:bg-gray-300 transition">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
