<?php require 'views/layouts/header.php'; ?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-8 border-b border-gray-200 text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h2>
            <p class="text-gray-500">Selesaikan pembayaran Anda sebelum batas waktu berakhir.</p>
            <div class="mt-4 text-3xl font-extrabold text-teal-600">
                <?= formatRupiah($order['total_amount']) ?>
            </div>
            <p class="text-sm text-gray-400 mt-1">ID Pesanan: #<?= $order['id'] ?></p>
        </div>

        <div class="p-6">
            <?php if ($order['status'] == 'paid'): ?>
                <div class="text-center py-8">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-check text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Pembayaran Berhasil</h3>
                    <p class="text-gray-500 mt-2">Tiket Anda telah diterbitkan.</p>
                    <a href="index.php?page=ticket&order_id=<?= $order['id'] ?>" class="mt-6 inline-block bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700">Lihat Tiket</a>
                </div>
            <?php else: ?>
                
                <?php if ($order['payment_method'] === 'va_bank_jateng'): ?>
                    <!-- Bank Jateng VA -->
                    <div class="text-center space-y-6">
                        <img src="https://www.bankjateng.co.id/assets/img/logo.png" alt="Bank Jateng" class="h-12 mx-auto">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nomor Virtual Account</p>
                            <div class="flex justify-center items-center space-x-2">
                                <span class="text-2xl font-mono font-bold text-gray-800">8888-0000-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
                                <button onclick="navigator.clipboard.writeText('88880000<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>')" class="text-teal-600 hover:text-teal-800"><i class="far fa-copy"></i></button>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg text-left text-sm text-blue-800 border border-blue-200">
                            <h4 class="font-bold mb-2"><i class="fas fa-info-circle"></i> Cara Pembayaran (Simulasi)</h4>
                            <p>Karena ini adalah mode simulasi/development:</p>
                            <ol class="list-decimal list-inside mt-2 space-y-1">
                                <li>Klik tombol <b>"Simulasi Pembayaran"</b> di bawah.</li>
                                <li>Anda akan diarahkan ke halaman simulasi Bank Jateng.</li>
                                <li>Lakukan konfirmasi pembayaran di halaman tersebut.</li>
                            </ol>
                        </div>

                        <a href="index.php?page=payment&id=<?= $order['id'] ?>&action=simulate_bank_jateng" class="block w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow transition transform hover:scale-105">
                            Simulasi Pembayaran Bank Jateng
                        </a>
                    </div>

                <?php elseif ($order['payment_method'] === 'qris'): ?>
                    <!-- QRIS -->
                    <div class="text-center">
                        <h3 class="font-bold text-gray-900 mb-4">Scan QRIS (ARCIS)</h3>
                        <div id="qrcode" class="flex justify-center mb-4"></div>
                        <p class="text-xs text-gray-500">NMID: ID1234567890123</p>
                        
                        <div class="mt-6 bg-gray-50 p-4 rounded text-sm">
                            <p>Silakan scan QR Code di atas menggunakan aplikasi pembayaran yang mendukung QRIS.</p>
                            <p class="mt-2 text-xs text-gray-400">Otomatis cek status pembayaran dalam 5 detik...</p>
                        </div>
                        
                        <!-- Manual Check Button for Demo -->
                        <a href="index.php?page=payment&id=<?= $order['id'] ?>&action=process_simulation" class="mt-4 inline-block text-teal-600 underline text-sm">
                            (Demo) Klik di sini jika sudah bayar
                        </a>
                    </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
                    <script>
                        // Generate dummy QRIS string
                        // Format: 00020101021226... (Standard QRIS)
                        const amount = <?= $order['total_amount'] ?>;
                        const qrisData = "00020101021226680016ID.CO.BANKJATENG.WWW011893600009" + amount + "5802ID5913Wisata Jan6013Semarang6304ABCD";
                        
                        new QRCode(document.getElementById("qrcode"), {
                            text: qrisData,
                            width: 200,
                            height: 200,
                            colorDark : "#000000",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    </script>

                <?php else: ?>
                    <!-- Other VA -->
                    <div class="text-center">
                        <p>Metode pembayaran: <?= strtoupper(str_replace('va_', 'Virtual Account ', $order['payment_method'])) ?></p>
                         <a href="index.php?page=payment&id=<?= $order['id'] ?>&action=process_simulation" class="mt-4 inline-block bg-teal-600 text-white px-6 py-2 rounded">
                            Simulasi Bayar
                        </a>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'views/layouts/footer.php'; ?>
