<?php
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6">
        <a href="index.php?page=admin_products" class="text-teal-600 hover:text-teal-800 font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">
            <?= isset($product) ? 'Edit Product' : 'Add New Product' ?>
        </h1>

        <form action="index.php?page=admin_products&action=<?= isset($product) ? 'update&id='.$product['id'] : 'store' ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>
            
            <!-- Product Type Selection -->
            <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Tiket / Produk</label>
                <select name="type" id="productType" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    <option value="tour" <?= (isset($product) && $product['type'] == 'tour') ? 'selected' : '' ?>>Tiket Destinasi Paket Wisata</option>
                    <option value="event" <?= (isset($product) && $product['type'] == 'event') ? 'selected' : '' ?>>Acara (Event)</option>
                    <option value="entrance" <?= (isset($product) && $product['type'] == 'entrance') ? 'selected' : '' ?>>Tiket Masuk Obyek Wisata</option>
                </select>
                <p class="mt-1 text-sm text-gray-600">Pilih jenis tiket di atas untuk menampilkan formulir yang sesuai.</p>
            </div>

            <!-- Common Fields -->
            <div class="space-y-6 border-b pb-6">
                <h3 class="text-lg font-medium text-gray-900">Informasi Dasar (Semua Jenis)</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Produk (Inggris)</label>
                        <input type="text" name="name_en" required value="<?= $product['name_en'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Produk (Indonesia)</label>
                        <input type="text" name="name_id" required value="<?= $product['name_id'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Link Video YouTube (Opsional)</label>
                    <input type="text" name="youtube_url" value="<?= $product['youtube_url'] ?? '' ?>" placeholder="https://www.youtube.com/watch?v=..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    <p class="mt-1 text-xs text-gray-500">Masukkan link lengkap YouTube. Video tidak akan ditampilkan jika kosong.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi (Inggris)</label>
                        <textarea name="description_en" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['description_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi (Indonesia)</label>
                        <textarea name="description_id" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['description_id'] ?? '' ?></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                    <input type="number" name="price" required value="<?= $product['price'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                        <?php if(isset($product) && !empty($product['image'])): ?>
                            <img src="img/<?= $product['image'] ?>" class="h-20 w-20 object-cover mb-2 rounded">
                        <?php endif; ?>
                        <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Galeri Foto (Banyak)</label>
                         <?php if(isset($gallery) && count($gallery) > 0): ?>
                            <div class="flex space-x-2 mb-2">
                                <?php foreach($gallery as $img): ?>
                                    <img src="img/<?= $img['image_path'] ?>" class="h-10 w-10 object-cover rounded">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="gallery[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    </div>
                </div>
            </div>

            <!-- TYPE SPECIFIC FIELDS -->

            <!-- 1. TOUR Fields -->
            <div id="tour-fields" class="type-fields space-y-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Detail Paket Wisata</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Durasi (contoh: "4 Hari 3 Malam")</label>
                    <input type="text" name="duration" value="<?= $product['duration'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rencana Perjalanan (Inggris)</label>
                        <textarea name="itinerary_en" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2" placeholder="Day 1: ..."><?= $product['itinerary_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rencana Perjalanan (Indonesia)</label>
                        <textarea name="itinerary_id" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2" placeholder="Hari 1: ..."><?= $product['itinerary_id'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fasilitas Termasuk (Inggris)</label>
                        <textarea name="facilities_en" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['facilities_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fasilitas Termasuk (Indonesia)</label>
                        <textarea name="facilities_id" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['facilities_id'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 2. EVENT Fields -->
            <div id="event-fields" class="type-fields space-y-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Detail Acara (Event)</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tagline (Inggris)</label>
                        <input type="text" name="tagline_en" value="<?= $product['tagline_en'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tagline (Indonesia)</label>
                        <input type="text" name="tagline_id" value="<?= $product['tagline_id'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal & Jam Mulai</label>
                        <input type="datetime-local" name="event_date" value="<?= isset($product['event_date']) ? date('Y-m-d\TH:i', strtotime($product['event_date'])) : '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal & Jam Selesai</label>
                        <input type="datetime-local" name="event_end_date" value="<?= isset($product['event_end_date']) ? date('Y-m-d\TH:i', strtotime($product['event_end_date'])) : '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kuota / Kapasitas</label>
                    <input type="number" name="quota" value="<?= $product['quota'] ?? 100 ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titik Kumpul (Inggris)</label>
                        <textarea name="meeting_point_en" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['meeting_point_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Titik Kumpul (Indonesia)</label>
                        <textarea name="meeting_point_id" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['meeting_point_id'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Yang Perlu Dibawa (Inggris)</label>
                        <textarea name="what_to_bring_en" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['what_to_bring_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Yang Perlu Dibawa (Indonesia)</label>
                        <textarea name="what_to_bring_id" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['what_to_bring_id'] ?? '' ?></textarea>
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Info Aksesibilitas (Inggris)</label>
                        <textarea name="accessibility_en" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['accessibility_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Info Aksesibilitas (Indonesia)</label>
                        <textarea name="accessibility_id" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['accessibility_id'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 3. ENTRANCE Fields -->
            <div id="entrance-fields" class="type-fields space-y-6 hidden">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Detail Tiket Masuk Obyek Wisata</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jam Buka (Inggris)</label>
                        <input type="text" name="opening_hours_en" value="<?= $product['opening_hours_en'] ?? '' ?>" placeholder="e.g. 08:00 AM - 05:00 PM" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jam Buka (Indonesia)</label>
                        <input type="text" name="opening_hours_id" value="<?= $product['opening_hours_id'] ?? '' ?>" placeholder="e.g. 08:00 - 17:00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat / Lokasi (Inggris)</label>
                        <textarea name="address_en" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['address_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat / Lokasi (Indonesia)</label>
                        <textarea name="address_id" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['address_id'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pengecualian / Exclusions (Inggris)</label>
                        <textarea name="exclusions_en" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['exclusions_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pengecualian / Exclusions (Indonesia)</label>
                        <textarea name="exclusions_id" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['exclusions_id'] ?? '' ?></textarea>
                    </div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Syarat Khusus (Inggris)</label>
                        <textarea name="terms_en" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['terms_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Syarat Khusus (Indonesia)</label>
                        <textarea name="terms_id" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2"><?= $product['terms_id'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon Kontak</label>
                        <input type="text" name="contact_phone" value="<?= $product['contact_phone'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email Kontak</label>
                        <input type="email" name="contact_email" value="<?= $product['contact_email'] ?? '' ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                </div>
            </div>

            <!-- SHARED: Policies (for all) & Location Map -->
            <div class="space-y-6 border-t pt-6">
                 <h3 class="text-lg font-medium text-gray-900">Kebijakan Umum & Lokasi Peta</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kebijakan Umum (Inggris)</label>
                        <textarea name="policy_en" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2" placeholder="Cancellation, Refund..."><?= $product['policy_en'] ?? '' ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kebijakan Umum (Indonesia)</label>
                        <textarea name="policy_id" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2" placeholder="Pembatalan, Refund..."><?= $product['policy_id'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Latitude (Garis Lintang)</label>
                        <input type="text" name="latitude" value="<?= $product['latitude'] ?? '' ?>" placeholder="-7.205..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Longitude (Garis Bujur)</label>
                        <input type="text" name="longitude" value="<?= $product['longitude'] ?? '' ?>" placeholder="109.914..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 sm:text-sm border p-2">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                    <?= isset($product) ? 'Update Produk' : 'Simpan Produk' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('productType');
        const fields = {
            'tour': document.getElementById('tour-fields'),
            'event': document.getElementById('event-fields'),
            'entrance': document.getElementById('entrance-fields')
        };

        function updateFields() {
            const selectedType = typeSelect.value;
            
            // Hide all first
            for (const key in fields) {
                if (fields[key]) {
                    fields[key].classList.add('hidden');
                }
            }

            // Show selected
            if (fields[selectedType]) {
                fields[selectedType].classList.remove('hidden');
            }
        }

        // Initial call
        updateFields();

        // Listen for changes
        typeSelect.addEventListener('change', updateFields);
    });
</script>
</div>
