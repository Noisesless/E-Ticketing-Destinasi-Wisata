<?php require 'views/layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 mb-8"><?= trans('Shopping Cart', 'Keranjang Belanja') ?></h1>

    <?php if (empty($cart)): ?>
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <p class="text-gray-500 mb-4"><?= trans('Your cart is empty.', 'Keranjang Anda kosong.') ?></p>
            <a href="index.php" class="text-teal-600 font-semibold hover:underline"><?= trans('Continue Shopping', 'Lanjut Belanja') ?></a>
        </div>
    <?php else: ?>
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            <div class="lg:col-span-7">
                <?php foreach ($cart as $index => $item): ?>
                    <div class="flex py-6 border-b border-gray-200">
                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                            <img src="img/<?= $item['image'] ?>" class="h-full w-full object-cover object-center">
                        </div>

                        <div class="ml-4 flex flex-1 flex-col">
                            <div>
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <h3><?= $item['name'] ?></h3>
                                    <p class="ml-4"><?= formatRupiah($item['price'] * $item['quantity']) ?></p>
                                </div>
                                <p class="mt-1 text-sm text-gray-500"><?= $item['visit_date'] ?></p>
                            </div>
                            <div class="flex flex-1 items-end justify-between text-sm">
                                <p class="text-gray-500">Qty <?= $item['quantity'] ?></p>

                                <div class="flex">
                                    <a href="index.php?page=cart&remove=<?= $index ?>" class="font-medium text-teal-600 hover:text-teal-500"><?= trans('Remove', 'Hapus') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">
                <h2 class="text-lg font-medium text-gray-900"><?= trans('Order Summary', 'Ringkasan Pesanan') ?></h2>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <div class="text-base font-medium text-gray-900"><?= trans('Order Total', 'Total Pesanan') ?></div>
                        <div class="text-base font-medium text-gray-900"><?= formatRupiah($total) ?></div>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="index.php?page=checkout" class="w-full flex justify-center rounded-md border border-transparent bg-teal-600 py-3 px-4 text-base font-medium text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 focus:ring-offset-gray-50">
                        <?= trans('Checkout', 'Pembayaran') ?>
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require 'views/layouts/footer.php'; ?>
