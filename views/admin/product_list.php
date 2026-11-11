<?php
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">Kelola Tiket / Produk</h1>
        <a href="index.php?page=admin_products&action=create" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 shadow">
            <i class="fas fa-plus mr-2"></i> Tambah Tiket Baru
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama (EN/ID)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php 
                $typeLabels = [
                    'tour' => 'Paket Wisata',
                    'event' => 'Acara',
                    'entrance' => 'Tiket Masuk'
                ];
                foreach ($products as $product): 
                ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($product['image']): ?>
                            <img src="img/<?= $product['image'] ?>" class="h-12 w-12 rounded object-cover">
                        <?php else: ?>
                            <div class="h-12 w-12 rounded bg-gray-200"></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= $product['name_en'] ?></div>
                        <div class="text-sm text-gray-500"><?= $product['name_id'] ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?= formatRupiah($product['price']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                            <?= $typeLabels[$product['type']] ?? $product['type'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="index.php?page=admin_products&action=edit&id=<?= $product['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                        <a href="index.php?page=admin_products&action=delete&id=<?= $product['id'] ?>&_csrf_token=<?= csrf_token() ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus tiket ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
