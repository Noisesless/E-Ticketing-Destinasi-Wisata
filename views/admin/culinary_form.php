<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><?= $action === 'edit' ? 'Edit Kuliner' : 'Tambah Kuliner Baru' ?></h1>
    </div>

    <form action="index.php?page=admin_culinary&action=<?= $action === 'edit' ? 'update&id='.$id : 'store' ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nama Tempat
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="<?= $culinary_spot['name'] ?? '' ?>" required>
            </div>

            <!-- Lokasi -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="location">
                    Lokasi
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="location" name="location" type="text" value="<?= $culinary_spot['location'] ?? '' ?>" required>
            </div>

            <!-- Menu Andalan -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="signature_dish">
                    Menu Andalan
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="signature_dish" name="signature_dish" type="text" value="<?= $culinary_spot['signature_dish'] ?? '' ?>" required>
            </div>

            <!-- Rating -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="rating">
                    Rating (0-5)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="rating" name="rating" type="number" step="0.1" min="0" max="5" value="<?= $culinary_spot['rating'] ?? '4.5' ?>" required>
            </div>

            <!-- Kisaran Harga -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price_range">
                    Kisaran Harga
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price_range" name="price_range" type="text" placeholder="Contoh: Rp 10.000 - Rp 50.000" value="<?= $culinary_spot['price_range'] ?? '' ?>" required>
            </div>

            <!-- Jam Buka -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="opening_hours">
                    Jam Buka
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="opening_hours" name="opening_hours" type="text" placeholder="Contoh: 08:00 - 21:00" value="<?= $culinary_spot['opening_hours'] ?? '' ?>" required>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Deskripsi
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" rows="4" required><?= $culinary_spot['description'] ?? '' ?></textarea>
        </div>

        <!-- Image -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                Foto
            </label>
            <?php if (!empty($culinary_spot['image'])): ?>
                <div class="mb-2">
                    <img src="img/<?= htmlspecialchars($culinary_spot['image']) ?>" alt="Current Image" class="h-32 object-cover rounded">
                </div>
            <?php endif; ?>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="image" name="image" type="file" accept="image/*" <?= $action === 'create' ? 'required' : '' ?>>
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG.</p>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                <?= $action === 'edit' ? 'Update' : 'Simpan' ?>
            </button>
            <a href="index.php?page=admin_culinary" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                Batal
            </a>
        </div>
    </form>
</div>
