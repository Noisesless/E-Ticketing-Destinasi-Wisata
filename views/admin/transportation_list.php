<?php
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Transportasi</h1>
    <a href="index.php?page=admin_transportation&action=create" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">
        <i class="fas fa-plus mr-2"></i> Tambah Layanan
    </a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
        <p class="font-bold">Sukses!</p>
        <p>Data berhasil <?= htmlspecialchars($_GET['msg']) ?>.</p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($transportations as $item): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($item['image']): ?>
                            <img src="img/<?= htmlspecialchars($item['image']) ?>" class="h-12 w-12 rounded object-cover">
                        <?php else: ?>
                            <span class="text-gray-400"><i class="fas fa-image fa-2x"></i></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></div>
                        <div class="text-sm text-gray-500"><?= htmlspecialchars($item['contact_number']) ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                            <?= htmlspecialchars($item['type']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($item['capacity']) ?> Orang
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= htmlspecialchars($item['price_range']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="index.php?page=admin_transportation&action=edit&id=<?= $item['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <a href="index.php?page=admin_transportation&action=delete&id=<?= $item['id'] ?>&_csrf_token=<?= csrf_token() ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-600 hover:text-red-900">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
