</main>
<footer class="bg-gray-800 text-white pt-10 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- DEVELOPMENT WARNING -->
        <div class="bg-red-600 border-l-4 border-yellow-400 text-white p-4 mb-8 rounded shadow-lg animate-pulse" role="alert">
            <div class="flex items-center">
                <div class="py-1"><i class="fas fa-exclamation-triangle text-3xl mr-4 text-yellow-300"></i></div>
                <div>
                    <p class="font-bold text-lg uppercase tracking-wider">PERINGATAN PENGEMBANGAN / DEVELOPMENT WARNING</p>
                    <p class="text-sm font-medium mt-1">
                        Website ini masih dalam tahap pengembangan (Under Construction). 
                        <span class="underline decoration-yellow-400 decoration-2 underline-offset-2">DILARANG MELAKUKAN TRANSAKSI APAPUN</span> melalui website ini.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <img src="img/logo.jpg" alt="Hollynice Logo" class="h-12 w-auto mb-4 rounded">
                <p class="mt-4 text-gray-400 text-sm">
                    <?= trans('Your best partner for exploring Banjarnegara. Trusted ticket bureau.', 'Partner terbaik Anda menjelajahi Banjarnegara. Biro tiket terpercaya.') ?>
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4"><?= trans('Contact', 'Kontak') ?></h3>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li><i class="fas fa-map-marker-alt mr-2"></i> Banjarnegara, Jawa Tengah</li>
                    <li><i class="fas fa-phone mr-2"></i> +62 822 2727 3733</li>
                    <li><i class="fas fa-envelope mr-2"></i> info@hollynice.com</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4"><?= trans('Payment Partners', 'Partner Pembayaran') ?></h3>
                <div class="flex space-x-4 text-2xl text-gray-400">
                    <i class="fab fa-cc-visa hover:text-white transition"></i>
                    <i class="fab fa-cc-mastercard hover:text-white transition"></i>
                    <i class="fas fa-qrcode hover:text-white transition" title="QRIS"></i>
                </div>
                <p class="mt-2 text-xs text-gray-500">Supported by QRIS</p>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-gray-500 text-xs">
            <div class="mb-4 md:mb-0">
                &copy; <?= date('Y') ?> Hollynice Bureau. All rights reserved. <br>
                Developed by <a href="https://www.clasnet.co.id" target="_blank" class="text-teal-400 hover:text-teal-300 transition">Clasnet</a>
            </div>
            <div class="flex space-x-6">
                <a href="index.php?page=disclaimer" class="hover:text-white transition"><?= trans('Disclaimer', 'Disclaimer') ?></a>
                <a href="index.php?page=privacy_policy" class="hover:text-white transition"><?= trans('Privacy Policy', 'Kebijakan Privasi') ?></a>
            </div>
        </div>
    </div>
</footer>

<!-- Mobile Bottom Nav -->
<?php include 'views/mobile/bottom_nav.php'; ?>

</body>
</html>
