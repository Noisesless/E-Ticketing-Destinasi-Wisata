<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                <?= trans('Important Contacts', 'Kontak Penting') ?>
            </h1>
            <p class="mt-4 text-lg text-gray-500">
                <?= trans('Emergency numbers and important contacts in Banjarnegara.', 'Nomor darurat dan kontak penting di Banjarnegara.') ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Hollynice Bureau -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-teal-500">
                <div class="flex items-center mb-4">
                    <div class="bg-teal-100 p-3 rounded-full mr-4">
                        <i class="fas fa-headset text-teal-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Hollynice Bureau</h3>
                        <p class="text-sm text-gray-500"><?= trans('Customer Service', 'Layanan Pelanggan') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-teal-500"></i>
                        <a href="tel:+6282227273733" class="hover:text-teal-600 font-semibold">+62 822 2727 3733</a>
                    </p>
                    <p class="flex items-center text-gray-700">
                        <i class="fab fa-whatsapp w-6 text-green-500"></i>
                        <a href="https://wa.me/6282227273733" class="hover:text-teal-600">+62 822 2727 3733</a>
                    </p>
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-envelope w-6 text-teal-500"></i>
                        <a href="mailto:info@hollynice.com" class="hover:text-teal-600">info@hollynice.com</a>
                    </p>
                </div>
            </div>

            <!-- Police -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-800">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-shield-alt text-blue-800 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Police Station', 'Kepolisian (Polres Banjarnegara)') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Emergency & Report', 'Darurat & Laporan') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-blue-800"></i>
                        <span class="font-bold">110</span> <span class="text-sm ml-2 text-gray-500">(Call Center)</span>
                    </p>
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone w-6 text-blue-800"></i>
                        <span>(0286) 591110</span>
                    </p>
                </div>
            </div>

            <!-- Fire Department -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-600">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <i class="fas fa-fire-extinguisher text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Fire Department', 'Pemadam Kebakaran (Damkar)') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Fire & Rescue', 'Kebakaran & Penyelamatan') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-red-600"></i>
                        <span class="font-bold">113</span>
                    </p>
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone w-6 text-red-600"></i>
                        <span>(0286) 592113</span>
                    </p>
                </div>
            </div>

            <!-- Hospital / Medical -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fas fa-hospital text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Hospitals', 'RSUD Hj. Anna Lasmanah') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Medical Emergency', 'Gawat Darurat Medis') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-green-600"></i>
                        <span class="font-bold">119</span>
                    </p>
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone w-6 text-green-600"></i>
                        <span>(0286) 591464</span>
                    </p>
                </div>
            </div>

            <!-- Red Cross (PMI) -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-400">
                <div class="flex items-center mb-4">
                    <div class="bg-red-50 p-3 rounded-full mr-4">
                        <i class="fas fa-heartbeat text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Red Cross', 'PMI Banjarnegara') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Ambulance & Blood Donor', 'Ambulans & Donor Darah') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone w-6 text-red-400"></i>
                        <span>(0286) 591018</span>
                    </p>
                </div>
            </div>

            <!-- BPBD / SAR -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-100 p-3 rounded-full mr-4">
                        <i class="fas fa-hard-hat text-orange-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">BPBD / SAR</h3>
                        <p class="text-sm text-gray-500"><?= trans('Disaster Response', 'Penanggulangan Bencana') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone w-6 text-orange-500"></i>
                        <span>(0286) 591234</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
