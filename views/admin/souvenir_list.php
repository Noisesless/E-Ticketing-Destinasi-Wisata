<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Souvenir</h1>
        <p class="text-gray-600">Kelola data produk souvenir dan oleh-oleh.</p>
    </div>
    <a href="index.php?page=admin_souvenir&action=create" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
        <i class="fas fa-plus mr-2"></i> Tambah Souvenir
    </a>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($souvenirs as $item): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php if (!empty($item['image'])): ?>
                        <img class="h-12 w-12 rounded object-cover" src="img/<?= htmlspecialchars($item['image']) ?>" alt="">
                    <?php else: ?>
                        <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center text-gray-500">
                            <i class="fas fa-gift"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></div>
                    <div class="text-sm text-gray-500 truncate w-64"><?= htmlspecialchars($item['description']) ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm font-bold text-gray-900">Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="index.php?page=admin_souvenir&action=edit&id=<?= $item['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                    <a href="index.php?page=admin_souvenir&action=delete&id=<?= $item['id'] ?>&_csrf_token=<?= csrf_token() ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-600 hover:text-red-900">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
