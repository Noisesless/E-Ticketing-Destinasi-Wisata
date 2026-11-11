<?php require 'views/layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-20 w-96 h-96 bg-pink-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <div class="max-w-md w-full space-y-8 bg-white/80 backdrop-blur-lg p-10 rounded-2xl shadow-2xl relative z-10 border border-white/50">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-teal-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                <i class="fas fa-user-shield text-teal-600 text-2xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                <?= trans('Welcome Back!', 'Selamat Datang Kembali!') ?>
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                <?= trans('Please sign in to continue', 'Silakan masuk untuk melanjutkan') ?>
            </p>
        </div>
        
        <form id="auth-form" class="mt-8 space-y-6" action="index.php?page=login" method="POST">
            <?= csrf_field() ?>

            <?php if (isset($_GET['registered'])): ?>
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-r-xl shadow-sm text-sm" role="alert">
                    <p class="font-bold flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <?= trans('Registration Successful', 'Pendaftaran Berhasil') ?>
                    </p>
                    <p class="mt-1 text-xs"><?= trans('Your account is ready. Please log in with your credentials.', 'Akun Anda telah aktif. Silakan masuk menggunakan kredensial Anda.') ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($retryAfter) && $retryAfter > 0): ?>
                <div id="rate-limit-card" class="bg-amber-50 border-l-4 border-amber-500 text-amber-900 p-4 rounded-r-xl shadow-sm text-sm" role="alert" data-seconds="<?= (int)$retryAfter ?>">
                    <div class="flex items-center space-x-2 font-bold text-amber-800 mb-1">
                        <i class="fas fa-shield-halved text-amber-600"></i>
                        <span><?= trans('Security Cooldown Active', 'Pembatasan Keamanan Aktif') ?></span>
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
                    <label for="username" class="sr-only">Username / Email</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tag text-gray-400"></i>
                    </div>
                    <input id="username" name="username" type="text" required 
                        value="<?= e($_POST['username'] ?? '') ?>"
                        class="appearance-none rounded-xl relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 sm:text-sm bg-gray-50 hover:bg-white" 
                        placeholder="<?= trans('Username or Email', 'Username atau Email') ?>">
                </div>
                
                <div class="relative">
                    <label for="password" class="sr-only">Password</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" required 
                        class="appearance-none rounded-xl relative block w-full pl-10 px-3 py-3 border border-gray-300 placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 sm:text-sm bg-gray-50 hover:bg-white" 
                        placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded cursor-pointer">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                        <?= trans('Remember me', 'Ingat saya') ?>
                    </label>
                </div>

                <div class="text-sm">
                    <a href="index.php?page=contacts" class="font-medium text-teal-600 hover:text-teal-500 transition-colors duration-300">
                        <?= trans('Forgot password?', 'Lupa password?') ?>
                    </a>
                </div>
            </div>

            <div>
                <button id="btn-submit" type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-teal-500 to-teal-700 hover:from-teal-600 hover:to-teal-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-teal-300 group-hover:text-white transition-colors duration-300"></i>
                    </span>
                    <span id="btn-text"><?= trans('Sign in', 'Masuk') ?></span>
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    <?= trans("Don't have an account?", "Belum punya akun?") ?>
                    <a href="index.php?page=register" class="font-bold text-teal-600 hover:text-teal-500 transition-colors duration-300">
                        <?= trans('Register here', 'Daftar di sini') ?>
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
                    btnText.textContent = "<?= trans('Sign in', 'Masuk') ?>";
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
