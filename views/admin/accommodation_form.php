<?php
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6">
        <a href="index.php?page=admin_accommodation" class="text-teal-600 hover:text-teal-800 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            <?= $isEdit ? 'Edit Penginapan' : 'Tambah Penginapan Baru' ?>
        </h1>

        <form action="index.php?page=admin_accommodation&action=<?= $isEdit ? 'update' : 'store' ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $accommodation['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Penginapan</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($accommodation['name']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                        <option value="hotel" <?= $accommodation['type'] == 'hotel' ? 'selected' : '' ?>>Hotel</option>
                        <option value="homestay" <?= $accommodation['type'] == 'homestay' ? 'selected' : '' ?>>Homestay</option>
                        <option value="villa" <?= $accommodation['type'] == 'villa' ? 'selected' : '' ?>>Villa</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                <input type="text" name="location" required value="<?= htmlspecialchars($accommodation['location']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2" placeholder="Alamat lengkap...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= htmlspecialchars($accommodation['description']) ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga per Malam (Rp)</label>
                    <input type="number" name="price_per_night" required value="<?= $accommodation['price_per_night'] ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rating (0.0 - 5.0)</label>
                    <input type="number" name="rating" step="0.1" min="0" max="5" required value="<?= $accommodation['rating'] ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Fasilitas (Pisahkan dengan koma)</label>
                <input type="text" name="facilities" value="<?= htmlspecialchars($accommodation['facilities']) ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2" placeholder="WiFi, AC, Kolam Renang...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Gambar</label>
                <?php if(!empty($accommodation['image']) && file_exists('img/' . $accommodation['image'])): ?>
                    <div class="mt-2 mb-2">
                        <img src="img/<?= htmlspecialchars($accommodation['image']) ?>" class="h-32 w-auto object-cover rounded">
                        <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</p>
            </div>

            <div class="pt-5 border-t border-gray-200">
                <div class="flex justify-end">
                    <a href="index.php?page=admin_accommodation" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        Batal
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Penginapan' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
