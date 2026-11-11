<?php
require 'views/layouts/header.php';
?>

<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                <?= trans('Important & Emergency Contacts', 'Kontak Penting & Darurat') ?>
            </h1>
            <p class="mt-4 text-lg text-gray-500">
                <?= trans('Emergency numbers and important contacts in Wonosobo.', 'Nomor darurat dan kontak penting di Wonosobo.') ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- E-Tiket Wonosobo -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-teal-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-teal-100 rounded-full text-teal-600 mr-4">
                        <i class="fas fa-ticket-alt text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">E-Tiket Wonosobo</h3>
                        <p class="text-sm text-gray-500"><?= trans('Customer Service', 'Layanan Pelanggan') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-teal-500"></i>
                        <a href="tel:+628110000000" class="hover:text-teal-600 font-semibold">+62 811 0000 000</a>
                    </p>
                    <p class="flex items-center text-gray-700">
                        <i class="fab fa-whatsapp w-6 text-green-500"></i>
                        <a href="https://wa.me/628110000000" class="hover:text-teal-600">+62 811 0000 000</a>
                    </p>
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-envelope w-6 text-teal-500"></i>
                        <a href="mailto:info@geopark.co.id" class="hover:text-teal-600">info@geopark.co.id</a>
                    </p>
                </div>
            </div>

            <!-- Polsek / Polres -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600 mr-4">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Police Station', 'Kepolisian (Polres Wonosobo)') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Emergency & Report', 'Darurat & Laporan') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-blue-500"></i>
                        <span class="font-semibold">110 / (0286) 321110</span>
                    </p>
                    <p class="text-sm text-gray-500">Jl. Bhayangkara No. 1, Wonosobo</p>
                </div>
            </div>

            <!-- Rumah Sakit -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-red-100 rounded-full text-red-600 mr-4">
                        <i class="fas fa-hospital text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Regional Public Hospital', 'RSUD KRT. Setjonegoro') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Medical Emergency', 'Darurat Medis / IGD') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-red-500"></i>
                        <span class="font-semibold">(0286) 321091</span>
                    </p>
                    <p class="text-sm text-gray-500">Jl. Setjonegoro No. 1, Wonosobo</p>
                </div>
            </div>

            <!-- BPBD -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-orange-100 rounded-full text-orange-600 mr-4">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Disaster Management', 'BPBD Wonosobo') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Search & Rescue (SAR)', 'Evakuasi & Bencana Alam') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-orange-500"></i>
                        <span class="font-semibold">(0286) 322971</span>
                    </p>
                    <p class="text-sm text-gray-500">Pusdalops BPBD Kabupaten Wonosobo</p>
                </div>
            </div>

            <!-- Pemadam Kebakaran -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-yellow-100 rounded-full text-yellow-600 mr-4">
                        <i class="fas fa-fire-extinguisher text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Fire Department', 'Pemadam Kebakaran (Damkar)') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Fire Emergency', 'Darurat Kebakaran') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-yellow-500"></i>
                        <span class="font-semibold">113 / (0286) 321113</span>
                    </p>
                </div>
            </div>

            <!-- PMI -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-rose-500">
                <div class="flex items-center mb-4">
                    <div class="p-3 bg-rose-100 rounded-full text-rose-600 mr-4">
                        <i class="fas fa-hand-holding-medical text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?= trans('Red Cross', 'PMI Wonosobo') ?></h3>
                        <p class="text-sm text-gray-500"><?= trans('Ambulance & Blood Donor', 'Ambulans & Donor Darah') ?></p>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="flex items-center text-gray-700">
                        <i class="fas fa-phone-alt w-6 text-rose-500"></i>
                        <span class="font-semibold">(0286) 321442</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
