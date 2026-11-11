<?php require 'views/layouts/header.php'; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900"><?= trans('Payment', 'Pembayaran') ?></h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500"><?= trans('Select your preferred payment method.', 'Pilih metode pembayaran Anda.') ?></p>
        </div>
        
        <form method="POST" id="checkout-form">
            <?= csrf_field() ?>
            <div class="p-6">
                <!-- Order Summary -->
                <div class="mb-8 p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                    <span class="text-gray-600 font-medium"><?= trans('Total Amount', 'Total Tagihan') ?></span>
                    <span class="text-2xl font-bold text-teal-600"><?= formatRupiah($total) ?></span>
                </div>

                <!-- Payment Methods -->
                <h4 class="text-md font-semibold text-gray-900 mb-4"><?= trans('Payment Method', 'Metode Pembayaran') ?></h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <!-- QRIS Option -->
                    <label class="relative border-2 rounded-lg p-4 cursor-pointer hover:border-teal-500 transition-all payment-option" data-target="qris-content">
                        <input type="radio" name="payment_method" value="qris" class="absolute top-4 right-4 text-teal-600 focus:ring-teal-500" checked onchange="togglePayment('qris')">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-qrcode text-4xl text-gray-700 mb-2"></i>
                            <span class="font-bold text-sm">QRIS</span>
                        </div>
                    </label>

                    <!-- Virtual Account Option -->
                    <label class="relative border-2 rounded-lg p-4 cursor-pointer hover:border-teal-500 transition-all payment-option" data-target="va-content">
                        <input type="radio" name="payment_method" value="va" class="absolute top-4 right-4 text-teal-600 focus:ring-teal-500" onchange="togglePayment('va')">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-university text-4xl text-gray-700 mb-2"></i>
                            <span class="font-bold text-sm">Virtual Account</span>
                        </div>
                    </label>

                    <!-- Credit Card Option -->
                    <label class="relative border-2 rounded-lg p-4 cursor-pointer hover:border-teal-500 transition-all payment-option" data-target="cc-content">
                        <input type="radio" name="payment_method" value="cc" class="absolute top-4 right-4 text-teal-600 focus:ring-teal-500" onchange="togglePayment('cc')">
                        <div class="flex flex-col items-center">
                            <i class="far fa-credit-card text-4xl text-gray-700 mb-2"></i>
                            <span class="font-bold text-sm">Credit Card</span>
                        </div>
                    </label>
                </div>

                <!-- QRIS Content -->
                <div id="qris-content" class="payment-details">
                    <div class="text-center bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <p class="mb-4 text-sm text-gray-500"><?= trans('Scan with GoPay, OVO, Dana, ShopeePay', 'Scan dengan GoPay, OVO, Dana, ShopeePay') ?></p>
                        <div class="bg-white p-4 inline-block rounded-lg shadow-sm">
                            <div id="payment-qr"></div>
                        </div>
                        <p class="mt-4 text-xs text-gray-400">NMID: ID1020023948573</p>
                    </div>
                </div>

                <!-- Virtual Account Content -->
                <div id="va-content" class="payment-details hidden">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 space-y-4">
                        
                        <!-- Bank Jateng Option -->
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 cursor-pointer hover:border-teal-500 ring-2 ring-teal-500">
                            <img src="https://www.bankjateng.co.id/assets/img/logo.png" class="h-8 w-auto mr-4" alt="Bank Jateng">
                            <div class="flex-1">
                                <p class="font-bold text-sm">Bank Jateng Lakupandai</p>
                                <p class="text-xs text-gray-500">Simulasi Pembayaran API</p>
                            </div>
                            <input type="radio" name="va_bank" value="bank_jateng" checked class="text-teal-600">
                        </div>

                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 cursor-pointer hover:border-teal-500">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="h-6 w-auto mr-4">
                            <div class="flex-1">
                                <p class="font-bold text-sm">BCA Virtual Account</p>
                                <p class="text-xs text-gray-500">Check automatically</p>
                            </div>
                            <input type="radio" name="va_bank" value="bca" checked class="text-teal-600">
                        </div>
                        <div class="flex items-center p-3 bg-white rounded border border-gray-200 cursor-pointer hover:border-teal-500">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" class="h-6 w-auto mr-4">
                            <div class="flex-1">
                                <p class="font-bold text-sm">Mandiri Virtual Account</p>
                                <p class="text-xs text-gray-500">Check automatically</p>
                            </div>
                            <input type="radio" name="va_bank" value="mandiri" class="text-teal-600">
                        </div>
                         <div class="flex items-center p-3 bg-white rounded border border-gray-200 cursor-pointer hover:border-teal-500">
                            <img src="https://upload.wikimedia.org/wikipedia/id/5/55/BNI_logo.svg" class="h-6 w-auto mr-4">
                            <div class="flex-1">
                                <p class="font-bold text-sm">BNI Virtual Account</p>
                                <p class="text-xs text-gray-500">Check automatically</p>
                            </div>
                            <input type="radio" name="va_bank" value="bni" class="text-teal-600">
                        </div>
                        
                        <div class="mt-4 p-4 bg-yellow-50 text-yellow-800 text-sm rounded border border-yellow-200">
                            <p class="font-bold"><i class="fas fa-info-circle mr-2"></i> Simulasi:</p>
                            <p class="mt-1">Nomor VA akan muncul setelah Anda klik tombol bayar.</p>
                        </div>
                    </div>
                </div>

                <!-- Credit Card Content -->
                <div id="cc-content" class="payment-details hidden">
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                         <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="card-number" class="block text-sm font-medium text-gray-700">Card number</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text" name="card-number" id="card-number" class="focus:ring-teal-500 focus:border-teal-500 block w-full pl-3 sm:text-sm border-gray-300 rounded-md py-2" placeholder="0000 0000 0000 0000">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="far fa-credit-card text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="expiration-date" class="block text-sm font-medium text-gray-700">Expiration date (MM/YY)</label>
                                <div class="mt-1">
                                    <input type="text" name="expiration-date" id="expiration-date" class="focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md py-2" placeholder="MM / YY">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                                <div class="mt-1">
                                    <input type="text" name="cvc" id="cvc" class="focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md py-2" placeholder="CVC">
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 p-4 bg-blue-50 text-blue-800 text-sm rounded border border-blue-200">
                             <p class="font-bold"><i class="fas fa-info-circle mr-2"></i> Simulasi:</p>
                             <p class="mt-1">Anda bisa memasukkan angka sembarang untuk testing.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-4 py-4 sm:px-6 bg-gray-50 flex justify-between items-center rounded-b-lg">
                <a href="index.php?page=cart" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-1"></i> <?= trans('Back', 'Kembali') ?>
                </a>
                <button type="submit" class="bg-teal-600 border border-transparent rounded-md shadow-sm py-3 px-8 inline-flex justify-center text-base font-bold text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                    <?= trans('Pay Now', 'Bayar Sekarang') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePayment(method) {
        // Hide all details
        document.querySelectorAll('.payment-details').forEach(el => el.classList.add('hidden'));
        
        // Reset border styles
        document.querySelectorAll('.payment-option').forEach(el => {
            el.classList.remove('border-teal-500', 'bg-teal-50');
            el.classList.add('border-gray-200');
        });

        // Show selected
        const content = document.getElementById(method + '-content');
        if (content) content.classList.remove('hidden');

        // Highlight selected option
        const option = document.querySelector(`input[value="${method}"]`).closest('label');
        option.classList.remove('border-gray-200');
        option.classList.add('border-teal-500', 'bg-teal-50');
    }

    // Initialize QR Code
    new QRCode(document.getElementById("payment-qr"), {
        text: "00020101021126570014ID.GO.GOPAY.WW01189360091430000000000000005204481453033605802ID5911HOLLYNICE6013BANJARNEGARA61055341162070703A016304",
        width: 150,
        height: 150
    });

    // Initialize first state
    togglePayment('qris');
</script>

<?php require 'views/layouts/footer.php'; ?>
