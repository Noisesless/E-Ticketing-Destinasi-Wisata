<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800"><?= $action === 'edit' ? 'Edit Souvenir' : 'Tambah Souvenir Baru' ?></h1>
    </div>

    <form action="index.php?page=admin_souvenir&action=<?= $action === 'edit' ? 'update&id='.$id : 'store' ?>" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nama Produk
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="<?= $souvenir['name'] ?? '' ?>" required>
            </div>

            <!-- Harga -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                    Harga (Rp)
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" name="price" type="number" min="0" value="<?= $souvenir['price'] ?? '' ?>" required>
            </div>
        </div>

        <!-- Deskripsi -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Deskripsi
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" rows="4" required><?= $souvenir['description'] ?? '' ?></textarea>
        </div>

        <!-- Image -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                Foto Produk
            </label>
            <?php if (!empty($souvenir['image'])): ?>
                <div class="mb-2">
                    <img src="img/<?= htmlspecialchars($souvenir['image']) ?>" alt="Current Image" class="h-32 object-cover rounded">
                </div>
            <?php endif; ?>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="image" name="image" type="file" accept="image/*" <?= $action === 'create' ? 'required' : '' ?>>
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG.</p>
        </div>

        <div class="flex items-center justify-between">
            <button class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                <?= $action === 'edit' ? 'Update' : 'Simpan' ?>
            </button>
            <a href="index.php?page=admin_souvenir" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                Batal
            </a>
        </div>
    </form>
</div>
