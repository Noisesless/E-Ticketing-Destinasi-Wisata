<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6"><?= trans('Privacy Policy', 'Kebijakan Privasi') ?></h1>
            
            <div class="prose prose-teal max-w-none text-gray-700">
                <p class="mb-4">
                    <?= trans('At Hollynice Bureau, accessible from our website, one of our main priorities is the privacy of our visitors. This Privacy Policy document contains types of information that is collected and recorded by Hollynice Bureau and how we use it.', 
                    'Di Hollynice Bureau, yang dapat diakses dari situs web kami, salah satu prioritas utama kami adalah privasi pengunjung kami. Dokumen Kebijakan Privasi ini berisi jenis informasi yang dikumpulkan dan dicatat oleh Hollynice Bureau dan bagaimana kami menggunakannya.') ?>
                </p>

                <h3 class="text-xl font-bold mt-6 mb-3"><?= trans('Information We Collect', 'Informasi yang Kami Kumpulkan') ?></h3>
                <p class="mb-4">
                    <?= trans('The personal information that you are asked to provide, and the reasons why you are asked to provide it, will be made clear to you at the point we ask you to provide your personal information.', 
                    'Informasi pribadi yang Anda diminta untuk berikan, dan alasan mengapa Anda diminta untuk memberikannya, akan dijelaskan kepada Anda pada saat kami meminta Anda untuk memberikan informasi pribadi Anda.') ?>
                </p>
                <ul class="list-disc pl-5 mb-4 space-y-2">
                    <li><?= trans('Name and contact information', 'Nama dan informasi kontak') ?></li>
                    <li><?= trans('Email address', 'Alamat email') ?></li>
                    <li><?= trans('Booking details', 'Detail pemesanan') ?></li>
                </ul>

                <h3 class="text-xl font-bold mt-6 mb-3"><?= trans('How We Use Your Information', 'Bagaimana Kami Menggunakan Informasi Anda') ?></h3>
                <p class="mb-4">
                    <?= trans('We use the information we collect in various ways, including to:', 'Kami menggunakan informasi yang kami kumpulkan dalam berbagai cara, termasuk untuk:') ?>
                </p>
                <ul class="list-disc pl-5 mb-4 space-y-2">
                    <li><?= trans('Provide, operate, and maintain our website', 'Menyediakan, mengoperasikan, dan memelihara situs web kami') ?></li>
                    <li><?= trans('Improve, personalize, and expand our website', 'Meningkatkan, mempersonalisasi, dan memperluas situs web kami') ?></li>
                    <li><?= trans('Understand and analyze how you use our website', 'Memahami dan menganalisis bagaimana Anda menggunakan situs web kami') ?></li>
                    <li><?= trans('Process your transactions and manage your orders', 'Memproses transaksi Anda dan mengelola pesanan Anda') ?></li>
                </ul>

                <h3 class="text-xl font-bold mt-6 mb-3"><?= trans('Cookies', 'Cookie') ?></h3>
                <p class="mb-4">
                    <?= trans('Like any other website, Hollynice Bureau uses "cookies". These cookies are used to store information including visitors preferences, and the pages on the website that the visitor accessed or visited.', 
                    'Seperti situs web lainnya, Hollynice Bureau menggunakan "cookie". Cookie ini digunakan untuk menyimpan informasi termasuk preferensi pengunjung, dan halaman-halaman di situs web yang diakses atau dikunjungi pengunjung.') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
