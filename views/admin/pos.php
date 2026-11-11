<?php
// Determine if we are inside the layout or need to load it
if (!isset($content)) {
    $content = __FILE__;
    require 'views/layouts/admin_layout.php';
    exit;
}
?>

<style>
    .scan-area {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
    }
    #reader video {
        border-radius: 0.75rem;
        object-fit: cover;
        width: 100% !important;
    }
    #reader {
        border: none !important;
    }
    #reader__scan_region {
        background: transparent !important;
    }
    #reader__dashboard_section_csr button {
        background-color: #0d9488 !important;
        color: white !important;
        padding: 6px 12px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        border: none !important;
        margin: 4px !important;
    }
    @keyframes scan {
        0% { top: 0%; opacity: 0; }
        15% { opacity: 1; }
        85% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
</style>

<!-- Page Header -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold font-heading text-gray-900 flex items-center gap-3">
            <span class="p-2 bg-teal-50 text-teal-600 rounded-lg inline-flex items-center justify-center">
                <i class="fas fa-qrcode text-xl"></i>
            </span>
            POS Scanner (Validasi Tiket)
        </h1>
        <p class="text-sm text-gray-500 mt-1">Pindai kode QR tiket atau masukkan kode secara manual untuk validasi akses masuk pengunjung.</p>
    </div>
    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-ping"></span>
            Scanner Siap
        </span>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    
    <!-- Scanner Card (Left) -->
    <div class="lg:col-span-6 flex flex-col space-y-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="mb-4 flex justify-between items-center">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-camera text-teal-600"></i>
                    Kamera Pemindai QR
                </h2>
                <span class="text-xs bg-teal-50 text-teal-700 font-medium px-2.5 py-1 rounded-full border border-teal-100">
                    <i class="fas fa-circle text-[8px] mr-1 text-teal-500"></i> Kamera Aktif
                </span>
            </div>
            
            <div class="scan-area bg-gray-900 relative flex items-center justify-center rounded-xl overflow-hidden min-h-[300px]">
                <div id="reader" class="w-full h-full"></div>
                <div class="absolute inset-0 border-2 border-teal-500/40 pointer-events-none rounded-xl z-10"></div>
                <!-- Scanning Animation Line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-teal-400 shadow-[0_0_12px_#2dd4bf] animate-[scan_2s_infinite] z-20 pointer-events-none"></div>
            </div>
            
            <p class="text-center text-xs text-gray-500 mt-3">
                Arahkan kode QR tiket pengunjung tepat ke dalam bingkai kamera pemindai.
            </p>
        </div>

        <!-- Manual Input Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-2">
                <i class="fas fa-keyboard text-gray-500"></i>
                Input Kode Tiket Manual
            </h3>
            <p class="text-xs text-gray-500 mb-3">Gunakan opsi ini jika kamera tidak dapat membaca QR atau pengunjung membawa kode cetak.</p>
            <form id="manual-ticket-form" onsubmit="handleManualSubmit(event)" class="flex gap-2">
                <input 
                    type="text" 
                    id="manual-ticket-code" 
                    placeholder="Contoh: TIKET-..." 
                    class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 font-mono"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-teal-600 hover:bg-teal-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition flex items-center gap-2"
                >
                    <i class="fas fa-check"></i>
                    <span>Verifikasi</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Result Card (Right) -->
    <div class="lg:col-span-6 flex flex-col">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 min-h-[420px] flex flex-col justify-center items-center text-center transition-all duration-300" id="result-container">
            
            <!-- Default Idle State -->
            <div id="default-state" class="flex flex-col items-center justify-center h-full py-8">
                <div class="w-20 h-20 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-5 shadow-inner">
                    <i class="fas fa-ticket-alt text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Menunggu Pemindaian Tiket</h3>
                <p class="text-gray-500 text-sm max-w-sm">
                    Arahkan kamera ke QR tiket atau ketik kode tiket secara manual untuk melihat rincian tiket dan status validasi.
                </p>
                <div class="mt-6 flex items-center gap-3 text-xs text-gray-400">
                    <span class="flex items-center gap-1"><i class="fas fa-shield-alt text-teal-500"></i> Verifikasi Real-time</span>
                    <span>•</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check-circle text-teal-500"></i> Anti-Double Entry</span>
                </div>
            </div>

            <!-- Loading State (Hidden by default) -->
            <div id="loading-state" class="hidden flex flex-col items-center justify-center h-full py-8">
                <div class="w-16 h-16 border-4 border-teal-200 border-t-teal-600 rounded-full animate-spin mb-4"></div>
                <p class="text-sm font-semibold text-gray-700">Memvalidasi tiket ke server...</p>
            </div>

            <!-- Success State (Hidden by default) -->
            <div id="success-state" class="hidden w-full text-left">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-inner">
                        <i class="fas fa-check text-3xl"></i>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 mb-1">
                        Tiket Valid
                    </span>
                    <h2 class="text-2xl font-bold font-heading text-gray-900">Akses Diterima</h2>
                    <p class="text-xs text-gray-500 font-mono mt-1" id="ticket-code-display">TIKET-CODE</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200/70 space-y-3 mb-6">
                    <div class="pb-3 border-b border-gray-200">
                        <span class="text-xs text-gray-500 uppercase tracking-wider block">Destinasi / Paket</span>
                        <p class="text-base font-bold text-gray-900 mt-0.5" id="ticket-type-display">Paket Wisata</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider block">Tanggal Kunjungan</span>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5" id="ticket-date-display">-</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wider block">Status Akses</span>
                            <span class="inline-flex items-center text-sm font-semibold text-emerald-600 mt-0.5">
                                <i class="fas fa-check-circle mr-1.5"></i> Masuk Berhasil
                            </span>
                        </div>
                    </div>
                </div>

                <button 
                    type="button" 
                    onclick="resetScanner()" 
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl transition shadow hover:shadow-md flex items-center justify-center gap-2"
                >
                    <i class="fas fa-qrcode"></i>
                    <span>Scan Tiket Berikutnya</span>
                </button>
            </div>

            <!-- Error State (Hidden by default) -->
            <div id="error-state" class="hidden w-full text-center">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                    <i class="fas fa-times text-3xl"></i>
                </div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-800 mb-1">
                    Validasi Gagal
                </span>
                <h2 class="text-2xl font-bold font-heading text-red-600 mb-2">Akses Ditolak</h2>
                <p class="text-gray-600 text-sm mb-6 max-w-md mx-auto" id="error-message">Tiket tidak valid atau sudah digunakan.</p>
                
                <button 
                    type="button" 
                    onclick="resetScanner()" 
                    class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-xl transition shadow flex items-center justify-center gap-2"
                >
                    <i class="fas fa-redo"></i>
                    <span>Coba Lagi</span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    let isScanning = true;
    let html5QrcodeScanner = null;

    function playBeep(isSuccess) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            if (isSuccess) {
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, ctx.currentTime);
                gain.gain.setValueAtTime(0.12, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.25);
            } else {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, ctx.currentTime);
                gain.gain.setValueAtTime(0.15, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.35);
            }
        } catch (e) {
            // AudioContext not supported or permission denied
        }
    }

    function showState(state) {
        document.getElementById('default-state').classList.toggle('hidden', state !== 'default');
        document.getElementById('loading-state').classList.toggle('hidden', state !== 'loading');
        document.getElementById('success-state').classList.toggle('hidden', state !== 'success');
        document.getElementById('error-state').classList.toggle('hidden', state !== 'error');
    }

    function validateTicketCode(code) {
        if (!code) return;
        isScanning = false;
        showState('loading');

        fetch('api/scan_ticket.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ticket_code: code })
        })
        .then(response => response.json())
        .then(data => {
            const resContainer = document.getElementById('result-container');
            if (data.success) {
                playBeep(true);
                showState('success');

                document.getElementById('ticket-code-display').innerText = code;
                document.getElementById('ticket-type-display').innerText = data.ticket.name_id || data.ticket.name_en || 'Tiket Destinasi';
                document.getElementById('ticket-date-display').innerText = data.ticket.visit_date || '-';

                resContainer.classList.add('ring-2', 'ring-emerald-400');
                setTimeout(() => resContainer.classList.remove('ring-2', 'ring-emerald-400'), 1000);
            } else {
                playBeep(false);
                showState('error');
                document.getElementById('error-message').innerText = data.message || 'Tiket tidak valid atau sudah digunakan.';

                resContainer.classList.add('ring-2', 'ring-red-400');
                setTimeout(() => resContainer.classList.remove('ring-2', 'ring-red-400'), 1000);
            }
        })
        .catch(err => {
            console.error('Validation error:', err);
            showState('error');
            document.getElementById('error-message').innerText = 'Terjadi gangguan koneksi ke server saat memverifikasi tiket.';
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (!isScanning) return;
        validateTicketCode(decodedText);
    }

    function onScanFailure(error) {
        // Silently ignore frame scan failures
    }

    function handleManualSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('manual-ticket-code');
        const code = input.value.trim();
        if (code) {
            validateTicketCode(code);
        }
    }

    function resetScanner() {
        isScanning = true;
        showState('default');
        const manualInput = document.getElementById('manual-ticket-code');
        if (manualInput) {
            manualInput.value = '';
            manualInput.focus();
        }
    }

    // Initialize Html5Qrcode Scanner safely
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Html5Qrcode !== 'undefined') {
            html5QrcodeScanner = new Html5Qrcode("reader");
            html5QrcodeScanner.start(
                { facingMode: "environment" }, 
                {
                    fps: 10,
                    qrbox: { width: 220, height: 220 }
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.warn("Camera start failed or permission denied:", err);
                const reader = document.getElementById('reader');
                if (reader) {
                    reader.innerHTML = `
                        <div class="text-white text-center p-6 text-xs">
                            <i class="fas fa-camera-slash text-gray-400 text-3xl mb-2 block"></i>
                            <p class="font-semibold text-gray-200 mb-1">Akses Kamera Belum Tersedia</p>
                            <p class="text-gray-400">Gunakan input kode tiket manual di bawah atau berikan izin akses kamera peramban.</p>
                        </div>
                    `;
                }
            });
        }
    });
</script>
