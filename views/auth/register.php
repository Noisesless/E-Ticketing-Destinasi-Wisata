<?php require 'views/layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <div class="max-w-md w-full space-y-8 bg-white/80 backdrop-blur-lg p-10 rounded-2xl shadow-2xl relative z-10 border border-white/50">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-teal-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                <i class="fas fa-user-plus text-teal-600 text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                <?= trans('Create Account', 'Buat Akun Baru') ?>
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                <?= trans('Join us and explore Geopark Wonosobo', 'Bergabunglah bersama kami dan jelajahi Geopark Wonosobo') ?>
            </p>
        </div>
        
        <form id="register-form" class="mt-8 space-y-6" action="index.php?page=register" method="POST">
            <?= csrf_field() ?>

            <?php if (!empty($retryAfter) && $retryAfter > 0): ?>
                <div id="rate-limit-card" class="bg-amber-50 border-l-4 border-amber-500 text-amber-900 p-4 rounded-r-xl shadow-sm text-sm" role="alert" data-seconds="<?= (int)$retryAfter ?>">
                    <div class="flex items-center space-x-2 font-bold text-amber-800 mb-1">
                        <i class="fas fa-shield-halved text-amber-600"></i>
                        <span><?= trans('Security Cooldown Active', 'Pembatasan Pendaftaran Aktif') ?></span>
                    </div>
                    <p class="text-xs text-amber-800 leading-relaxed"><?= e($error ?? $rateLimitWarning) ?></p>
                    <div class="mt-3 pt-2 border-t border-amber-200/60 flex items-center justify-between text-xs text-amber-800 font-mono">
                        <span class="flex items-center gap-1.5"><i class="fas fa-hourglass-start animate-spin text-amber-600"></i> <?= trans('Cooldown time:', 'Sisa jeda:') ?></span>
                        <span id="countdown-val" class="font-bold text-amber-900 bg-amber-100 px-2 py-0.5 rounded"><?= (int)$retryAfter ?> detik</span>
                    </div>
                </div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm" role="alert">
                    <p class="font-bold flex items-center gap-2"><i class="fas fa-circle-exclamation text-red-500"></i> <?= trans('Error', 'Kesalahan') ?></p>
                    <p class="text-xs mt-1 leading-relaxed"><?= e($error) ?></p>
                </div>
            <?php elseif (!empty($rateLimitWarning)): ?>
                <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-800 p-4 rounded-r-lg shadow-sm text-sm" role="alert">
                    <p class="font-bold flex items-center gap-2"><i class="fas fa-triangle-exclamation text-amber-600"></i> <?= trans('Notice', 'Pemberitahuan') ?></p>
                    <p class="text-xs mt-1"><?= e($rateLimitWarning) ?></p>
                </div>
            <?php endif; ?>
            
            <div class="space-y-4">
                <div class="relative">
                    <label for="full_name" class="sr-only">Full Name</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-id-card text-gray-400"></i>
                    </div>
                    <input id="full_name" name="full_name" type="text" required 
                        value="<?= e($_POST['full_name'] ?? '') ?>"
                        class="appearance-none rounded-xl relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 sm:text-sm bg-gray-50 hover:bg-white" 
                        placeholder="<?= trans('Full Name', 'Nama Lengkap') ?>">
                </div>

                <div class="relative">
                    <label for="username" class="sr-only">Username</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input id="username" name="username" type="text" required 
                        value="<?= e($_POST['username'] ?? '') ?>"
                        class="appearance-none rounded-xl relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 sm:text-sm bg-gray-50 hover:bg-white" 
                        placeholder="Username (huruf/angka/garis bawah)">
                </div>
                
                <div class="relative">
                    <label for="email" class="sr-only">Email</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" required 
                        value="<?= e($_POST['email'] ?? '') ?>"
                        class="appearance-none rounded-xl relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 sm:text-sm bg-gray-50 hover:bg-white" 
                        placeholder="<?= trans('Email Address', 'Alamat Email') ?>">
                </div>

                <div class="relative">
                    <label for="password" class="sr-only">Password</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none rounded-xl relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 sm:text-sm bg-gray-50 hover:bg-white" 
                        placeholder="<?= trans('Password (min. 6 characters)', 'Password (minimal 6 karakter)') ?>">
                </div>
            </div>

            <div>
                <button id="btn-submit" type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-teal-500 to-teal-700 hover:from-teal-600 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-teal-300 group-hover:text-white transition-colors duration-300"></i>
                    </span>
                    <span id="btn-text"><?= trans('Register', 'Daftar') ?></span>
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    <?= trans("Already have an account?", "Sudah punya akun?") ?>
                    <a href="index.php?page=login" class="font-bold text-teal-600 hover:text-teal-500 transition-colors duration-300">
                        <?= trans('Sign in here', 'Masuk di sini') ?>
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const card = document.getElementById('rate-limit-card');
    const timerElem = document.getElementById('countdown-val');
    const submitBtn = document.getElementById('btn-submit');
    const btnText = document.getElementById('btn-text');

    if (card && timerElem && submitBtn) {
        let sec = parseInt(card.getAttribute('data-seconds'), 10) || 0;
        if (sec > 0) {
            submitBtn.disabled = true;
            btnText.textContent = "<?= trans('Cooldown Active...', 'Harap Menunggu...') ?>";

            const interval = setInterval(function() {
                sec--;
                if (sec <= 0) {
                    clearInterval(interval);
                    timerElem.textContent = "<?= trans('Done! Ready to try again.', 'Selesai! Silakan coba lagi.') ?>";
                    submitBtn.disabled = false;
                    btnText.textContent = "<?= trans('Register', 'Daftar') ?>";
                    card.classList.remove('bg-amber-50', 'border-amber-500', 'text-amber-900');
                    card.classList.add('bg-emerald-50', 'border-emerald-500', 'text-emerald-900');
                } else {
                    timerElem.textContent = sec + " detik";
                }
            }, 1000);
        }
    }
});
</script>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
</style>
<?php require 'views/layouts/footer.php'; ?>
