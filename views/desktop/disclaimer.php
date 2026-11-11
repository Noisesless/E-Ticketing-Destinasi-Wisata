<?php require 'views/layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Disclaimer</h1>
            
            <div class="prose prose-teal max-w-none text-gray-700">
                <p class="mb-4">
                    <?= trans('The information provided by Hollynice Bureau ("we," "us," or "our") on this website is for general informational purposes only. All information on the Site is provided in good faith, however we make no representation or warranty of any kind, express or implied, regarding the accuracy, adequacy, validity, reliability, availability, or completeness of any information on the Site.', 
                    'Informasi yang disediakan oleh Hollynice Bureau ("kami") di situs web ini hanya untuk tujuan informasi umum. Semua informasi di Situs ini disediakan dengan itikad baik, namun kami tidak membuat pernyataan atau jaminan dalam bentuk apa pun, tersurat maupun tersirat, mengenai keakuratan, kecukupan, validitas, keandalan, ketersediaan, atau kelengkapan informasi apa pun di Situs ini.') ?>
                </p>

                <h3 class="text-xl font-bold mt-6 mb-3"><?= trans('External Links Disclaimer', 'Sanggahan Tautan Eksternal') ?></h3>
                <p class="mb-4">
                    <?= trans('The Site may contain (or you may be sent through the Site) links to other websites or content belonging to or originating from third parties or links to websites and features in banners or other advertising. Such external links are not investigated, monitored, or checked for accuracy, adequacy, validity, reliability, availability, or completeness by us.', 
                    'Situs ini mungkin berisi (atau Anda mungkin dikirim melalui Situs) tautan ke situs web lain atau konten milik atau berasal dari pihak ketiga atau tautan ke situs web dan fitur dalam spanduk atau iklan lainnya. Tautan eksternal tersebut tidak kami selidiki, pantau, atau periksa keakuratan, kecukupan, validitas, keandalan, ketersediaan, atau kelengkapannya.') ?>
                </p>

                <h3 class="text-xl font-bold mt-6 mb-3"><?= trans('Limitation of Liability', 'Batasan Tanggung Jawab') ?></h3>
                <p class="mb-4">
                    <?= trans('In no event shall we have any liability to you for any loss or damage of any kind incurred as a result of the use of the site or reliance on any information provided on the site. Your use of the site and your reliance on any information on the site is solely at your own risk.', 
                    'Dalam keadaan apa pun kami tidak bertanggung jawab kepada Anda atas kerugian atau kerusakan apa pun yang timbul akibat penggunaan situs atau ketergantungan pada informasi apa pun yang disediakan di situs. Penggunaan Anda atas situs dan ketergantungan Anda pada informasi apa pun di situs sepenuhnya merupakan risiko Anda sendiri.') ?>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
