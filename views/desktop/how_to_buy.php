<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                <?= trans('How to Buy Tickets', 'Cara Pembelian Tiket') ?>
            </h1>
            <p class="mt-4 text-lg text-gray-500">
                <?= trans('Follow these simple steps to purchase your tickets.', 'Ikuti langkah-langkah mudah berikut untuk membeli tiket Anda.') ?>
            </p>
        </div>

        <div class="mt-12">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    <!-- Step 1 -->
                    <li>
                        <div class="relative pb-12">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center ring-8 ring-white text-white font-bold">
                                        1
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <h3 class="text-lg font-bold text-gray-900"><?= trans('Select Product', 'Pilih Produk') ?></h3>
                                    <p class="mt-1 text-gray-500 mb-4"><?= trans('Browse through our Tours, Events, or Entrance Tickets. Click on the product you are interested in to view details.', 'Jelajahi Paket Tur, Acara, atau Tiket Masuk kami. Klik produk yang Anda minati untuk melihat detailnya.') ?></p>
                                    <img src="img/pembayaran/1. pilih-produk.JPG" alt="Pilih Produk" class="w-full md:w-3/4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Step 2 -->
                    <li>
                        <div class="relative pb-12">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center ring-8 ring-white text-white font-bold">
                                        2
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <h3 class="text-lg font-bold text-gray-900"><?= trans('Choose Date & Quantity', 'Pilih Tanggal & Jumlah') ?></h3>
                                    <p class="mt-1 text-gray-500 mb-4"><?= trans('Select your preferred visit date and the number of tickets you wish to purchase. Then click "Add to Cart".', 'Pilih tanggal kunjungan yang Anda inginkan dan jumlah tiket yang ingin Anda beli. Lalu klik "Masukkan Keranjang".') ?></p>
                                    <img src="img/pembayaran/2. pilih-tanggal-dan-jumlah.JPG" alt="Pilih Tanggal dan Jumlah" class="w-full md:w-3/4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Step 3 -->
                    <li>
                        <div class="relative pb-12">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center ring-8 ring-white text-white font-bold">
                                        3
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <h3 class="text-lg font-bold text-gray-900"><?= trans('Login or Register', 'Login atau Daftar') ?></h3>
                                    <p class="mt-1 text-gray-500 mb-4"><?= trans('You need to login to proceed. If you don\'t have an account, please register first.', 'Anda perlu login untuk melanjutkan. Jika belum memiliki akun, silakan mendaftar terlebih dahulu.') ?></p>
                                    <img src="img/pembayaran/3. login-atau-daftar.JPG" alt="Login atau Daftar" class="w-full md:w-3/4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Step 4 -->
                    <li>
                        <div class="relative pb-12">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center ring-8 ring-white text-white font-bold">
                                        4
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <h3 class="text-lg font-bold text-gray-900"><?= trans('Review Order', 'Konfirmasi Pesanan') ?></h3>
                                    <p class="mt-1 text-gray-500 mb-4"><?= trans('Review your order details. Make sure everything is correct before proceeding.', 'Periksa kembali detail pesanan Anda. Pastikan semuanya benar sebelum melanjutkan.') ?></p>
                                    <img src="img/pembayaran/4. pembayaran.JPG" alt="Konfirmasi Pesanan" class="w-full md:w-3/4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                </div>
                            </div>
                        </div>
                    </li>

                     <!-- Step 5 -->
                     <li>
                        <div class="relative pb-12">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center ring-8 ring-white text-white font-bold">
                                        5
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <h3 class="text-lg font-bold text-gray-900"><?= trans('Select Payment Method', 'Pilih Cara Pembayaran') ?></h3>
                                    <p class="mt-1 text-gray-500 mb-4"><?= trans('Choose your preferred payment method (Bank Transfer, E-Wallet, etc) and complete the payment.', 'Pilih metode pembayaran yang Anda inginkan (Transfer Bank, E-Wallet, dll) dan selesaikan pembayaran.') ?></p>
                                    <img src="img/pembayaran/5. pilih-cara-pembayaran.JPG" alt="Pilih Cara Pembayaran" class="w-full md:w-3/4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Step 6 -->
                    <li>
                        <div class="relative pb-12">
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center ring-8 ring-white text-white font-bold">
                                        6
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <h3 class="text-lg font-bold text-gray-900"><?= trans('Receive E-Ticket', 'Terima E-Ticket') ?></h3>
                                    <p class="mt-1 text-gray-500 mb-4"><?= trans('After payment is confirmed, your E-Ticket will be available in your dashboard and sent to your email.', 'Setelah pembayaran dikonfirmasi, E-Ticket Anda akan tersedia di dashboard dan dikirim ke email Anda.') ?></p>
                                    <img src="img/pembayaran/6. dapat-tiket.JPG" alt="Terima E-Ticket" class="w-full md:w-3/4 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                </div>
                            </div>
                        </div>
                    </li>

                    <!-- Step 7 -->
                    <li>
                        <div class="relative pb-12">
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center ring-8 ring-white text-white font-bold">
                                        7
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5">
                                    <h3 class="text-lg font-bold text-gray-900"><?= trans('Scan at Location', 'Scan di Lokasi') ?></h3>
                                    <p class="mt-1 text-gray-500 mb-4">
                                        <?= trans('Show your E-Ticket (QR Code) to the operator at the ticket counter or entrance to be scanned.', 'Tunjukkan E-Ticket (QR Code) Anda kepada operator di loket atau pintu masuk untuk dipindai (scan).') ?>
                                    </p>
                                    
                                    <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded shadow-sm w-full md:w-3/4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-qrcode text-teal-600 text-4xl"></i>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm text-teal-800 font-bold uppercase mb-1">
                                                    <?= trans('Important Instruction', 'Instruksi Penting') ?>
                                                </p>
                                                <p class="text-sm text-teal-700">
                                                    <?= trans('Please ensure your phone screen brightness is set to high for easier scanning by the operator.', 'Mohon pastikan kecerahan layar HP Anda diatur maksimal agar QR Code mudah dipindai oleh operator.') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="mt-10 text-center">
            <a href="index.php" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 md:text-lg shadow-lg transform hover:-translate-y-1 transition">
                <?= trans('Start Shopping', 'Mulai Belanja Sekarang') ?>
            </a>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
