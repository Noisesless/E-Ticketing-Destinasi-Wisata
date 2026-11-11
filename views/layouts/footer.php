<!-- Footer -->
<footer class="bg-gray-800 text-white pt-10 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center mb-4">
                    <img src="img/logo.webp" alt="E-Tiket Wonosobo Logo" class="h-12 w-auto mr-3 rounded">
                    <div class="flex flex-col leading-tight">
                        <span class="text-base font-semibold text-white">E-Tiket Wonosobo</span>
                        <span class="text-xs text-gray-400">Wisata Geopark</span>
                    </div>
                </div>
                <p class="mt-4 text-gray-400 text-sm">
                    <?= trans(
                        'Official e-ticketing platform for Wonosobo Geopark destinations.',
                        'Platform e-tiket resmi destinasi wisata Geopark Wonosobo.'
                    ) ?>
                </p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4"><?= trans('Contact', 'Kontak') ?></h3>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li><i class="fas fa-map-marker-alt mr-2"></i> Wonosobo, Jawa Tengah</li>
                    <li><i class="fas fa-phone mr-2"></i> +62 811 0000 000</li>
                    <li><i class="fas fa-envelope mr-2"></i> info@geopark.co.id</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4"><?= trans('Legal', 'Informasi Hukum') ?></h3>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li><a href="index.php?page=privacy_policy" class="hover:text-white"><?= trans('Privacy Policy', 'Kebijakan Privasi') ?></a></li>
                    <li><a href="index.php?page=disclaimer" class="hover:text-white"><?= trans('Disclaimer', 'Penafian') ?></a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-700 flex flex-col md:flex-row justify-between items-center text-gray-500 text-xs">
            <div class="mb-4 md:mb-0">
                &copy; <?= date('Y') ?> E-Tiket Wonosobo. All rights reserved. <br>
                Developed by <a href="https://www.clasnet.co.id" target="_blank" class="text-teal-400 hover:text-teal-300 transition">Clasnet</a>
            </div>
            <div class="flex space-x-6">
                <!-- Social links if needed -->
            </div>
        </div>
    </div>
</footer>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
</body>
</html>
