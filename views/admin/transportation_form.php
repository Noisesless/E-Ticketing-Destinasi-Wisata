<?php
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800"><?= $action == 'edit' ? 'Edit' : 'Tambah' ?> Layanan Transportasi</h1>
        <a href="index.php?page=admin_transportation" class="text-teal-600 hover:text-teal-800 mt-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
        <form action="index.php?page=admin_transportation&action=<?= $action ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <?php if ($action == 'edit'): ?>
                <input type="hidden" name="id" value="<?= $transportation['id'] ?>">
                <input type="hidden" name="old_image" value="<?= $transportation['image'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Layanan -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan / Armada</label>
                    <input type="text" name="name" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 border p-2" value="<?= $transportation['name'] ?? '' ?>">
                </div>

                <!-- Tipe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Layanan</label>
                    <select name="type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 border p-2">
                        <option value="rental" <?= (isset($transportation) && $transportation['type'] == 'rental') ? 'selected' : '' ?>>Rental Mobil (Lepas Kunci/Supir)</option>
                        <option value="travel" <?= (isset($transportation) && $transportation['type'] == 'travel') ? 'selected' : '' ?>>Travel (Antar Kota)</option>
                        <option value="pickup" <?= (isset($transportation) && $transportation['type'] == 'pickup') ? 'selected' : '' ?>>Pickup / Ojek Wisata</option>
                    </select>
                </div>

                <!-- Kapasitas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Penumpang (Orang)</label>
                    <input type="number" name="capacity" required min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 border p-2" value="<?= $transportation['capacity'] ?? '4' ?>">
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kisaran Harga</label>
                    <input type="text" name="price_range" required placeholder="Contoh: Rp 300.000 / 24 jam" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 border p-2" value="<?= $transportation['price_range'] ?? '' ?>">
                </div>

                <!-- Kontak -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                    <input type="text" name="contact_number" required placeholder="08123456789" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 border p-2" value="<?= $transportation['contact_number'] ?? '' ?>">
                </div>

                <!-- Deskripsi -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 border p-2"><?= $transportation['description'] ?? '' ?></textarea>
                </div>

                <!-- Fasilitas -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas (Pisahkan dengan koma)</label>
                    <input type="text" name="facilities" placeholder="AC, Musik, Air Mineral, Charger" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 border p-2" value="<?= $transportation['facilities'] ?? '' ?>">
                </div>

                <!-- Gambar -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Armada</label>
                    <?php if (isset($transportation['image']) && $transportation['image']): ?>
                        <div class="mb-2">
                            <img src="img/<?= htmlspecialchars($transportation['image']) ?>" alt="Current Image" class="h-32 object-cover rounded">
                            <p class="text-xs text-gray-500 mt-1">Gambar saat ini</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 shadow-lg transform transition hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
