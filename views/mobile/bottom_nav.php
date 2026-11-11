<?php
$page = $_GET['page'] ?? 'home';
$type = $_GET['type'] ?? '';
?>
<div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] flex justify-around items-center py-2 pb-safe z-50 md:hidden h-[70px]">
    <!-- Home -->
    <a href="index.php" class="flex flex-col items-center justify-center w-full h-full space-y-1 <?= ($page == 'home' && $type == '') ? 'text-teal-600' : 'text-gray-400 hover:text-teal-500' ?>">
        <i class="fas fa-home text-xl mb-0.5"></i>
        <span class="text-[10px] font-medium"><?= trans('Home', 'Beranda') ?></span>
    </a>
    
    <!-- Explore -->
    <a href="index.php?type=all" class="flex flex-col items-center justify-center w-full h-full space-y-1 <?= ($type != '') ? 'text-teal-600' : 'text-gray-400 hover:text-teal-500' ?>">
        <i class="fas fa-compass text-xl mb-0.5"></i>
        <span class="text-[10px] font-medium"><?= trans('Explore', 'Jelajah') ?></span>
    </a>

    <!-- Tickets / Cart -->
    <a href="index.php?page=cart" class="flex flex-col items-center justify-center w-full h-full space-y-1 relative <?= ($page == 'cart') ? 'text-teal-600' : 'text-gray-400 hover:text-teal-500' ?>">
        <div class="relative">
            <i class="fas fa-ticket-alt text-xl mb-0.5"></i>
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="absolute -top-1.5 -right-2 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border border-white leading-none">
                    <?= count($_SESSION['cart']) ?>
                </span>
            <?php endif; ?>
        </div>
        <span class="text-[10px] font-medium"><?= trans('My Ticket', 'Tiket') ?></span>
    </a>

    <!-- Info / Contacts -->
    <a href="index.php?page=contacts" class="flex flex-col items-center justify-center w-full h-full space-y-1 <?= ($page == 'contacts') ? 'text-teal-600' : 'text-gray-400 hover:text-teal-500' ?>">
        <i class="fas fa-info-circle text-xl mb-0.5"></i>
        <span class="text-[10px] font-medium"><?= trans('Info', 'Info') ?></span>
    </a>

    <!-- Account / Login -->
    <?php if (isLoggedIn()): ?>
        <a href="index.php?page=logout" onclick="return confirm('<?= trans('Are you sure you want to logout?', 'Yakin ingin keluar?') ?>')" class="flex flex-col items-center justify-center w-full h-full space-y-1 text-gray-400 hover:text-red-500">
            <i class="fas fa-user-circle text-xl mb-0.5"></i>
            <span class="text-[10px] font-medium"><?= trans('Logout', 'Keluar') ?></span>
        </a>
    <?php else: ?>
        <a href="index.php?page=login" class="flex flex-col items-center justify-center w-full h-full space-y-1 <?= ($page == 'login') ? 'text-teal-600' : 'text-gray-400 hover:text-teal-500' ?>">
            <i class="fas fa-sign-in-alt text-xl mb-0.5"></i>
            <span class="text-[10px] font-medium"><?= trans('Login', 'Masuk') ?></span>
        </a>
    <?php endif; ?>
</div>
<style>
    /* Safe area padding for iPhones with notch */
    .pb-safe {
        padding-bottom: env(safe-area-inset-bottom);
    }
</style>
