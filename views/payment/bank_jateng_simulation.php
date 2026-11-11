<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Jateng Payment Simulation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #e5e7eb; }
        .bank-header { background: linear-gradient(90deg, #d32f2f 0%, #b71c1c 100%); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bank-header p-6 text-white text-center relative">
            <div class="absolute top-4 left-4">
                 <img src="https://www.bankjateng.co.id/assets/img/logo_white.png" alt="Bank Jateng" class="h-8 opacity-90"> 
                 <!-- Fallback text if logo fails -->
                 <span class="font-bold text-xl ml-2">Bank Jateng</span>
            </div>
            <div class="mt-8">
                <h2 class="text-2xl font-bold">Konfirmasi Pembayaran</h2>
                <p class="text-red-100 text-sm">Laku Pandai Payment Gateway</p>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">
            <div class="space-y-6">
                
                <!-- Merchant Info -->
                <div class="flex items-center justify-between border-b pb-4">
                    <span class="text-gray-500">Merchant</span>
                    <span class="font-bold text-gray-800">WISATA JAN</span>
                </div>

                <!-- Transaction Details -->
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Nomor VA</span>
                        <span class="font-mono font-medium">8888-0000-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">ID Transaksi</span>
                        <span class="font-mono"><?= $order['transaction_id'] ?></span>
                    </div>
                    <div class="flex justify-between items-end mt-4">
                        <span class="text-gray-500 font-medium">Total Tagihan</span>
                        <span class="text-2xl font-bold text-red-700">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                    </div>
                </div>

                <!-- Auth Simulation -->
                <div class="bg-gray-50 p-4 rounded border border-gray-200 mt-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">MPIN (Simulasi)</label>
                    <div class="flex space-x-2">
                        <input type="password" value="123456" readonly class="w-full border-gray-300 rounded p-2 bg-white text-center tracking-widest" disabled>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">*MPIN otomatis terisi untuk simulasi</p>
                </div>

                <!-- Action Button -->
                <form action="index.php" method="GET">
                    <input type="hidden" name="page" value="payment">
                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                    <input type="hidden" name="action" value="process_simulation">
                    
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-lg shadow-lg transform transition active:scale-95 flex justify-center items-center">
                        <i class="fas fa-lock mr-2"></i> BAYAR SEKARANG
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="index.php?page=payment&id=<?= $order['id'] ?>" class="text-gray-500 text-sm hover:underline">Batalkan</a>
                </div>

            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-100 p-4 text-center text-xs text-gray-400 border-t">
            &copy; 2024 Bank Jateng. All rights reserved.<br>
            Secure Payment Simulation
        </div>
    </div>

</body>
</html>
