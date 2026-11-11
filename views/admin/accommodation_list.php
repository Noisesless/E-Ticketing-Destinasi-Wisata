<?php
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900">Kelola Penginapan</h1>
        <a href="index.php?page=admin_accommodation&action=create" class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 shadow">
            <i class="fas fa-plus mr-2"></i> Tambah Penginapan
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga/Malam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($accommodations as $item): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($item['image'] && file_exists('img/' . $item['image'])): ?>
                            <img src="img/<?= htmlspecialchars($item['image']) ?>" class="h-12 w-12 rounded object-cover">
                        <?php else: ?>
                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fas fa-bed"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="text-xs text-gray-500"><?= htmlspecialchars($item['location']) ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                            <?= htmlspecialchars($item['type']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?= formatRupiah($item['price_per_night']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <?= $item['rating'] ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="index.php?page=admin_accommodation&action=edit&id=<?= $item['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                        <a href="index.php?page=admin_accommodation&action=delete&id=<?= $item['id'] ?>&_csrf_token=<?= csrf_token() ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus penginapan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
