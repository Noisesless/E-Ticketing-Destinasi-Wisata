<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Scanner - Operator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .scan-area {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
        }
        #reader video {
            border-radius: 1rem;
            object-fit: cover;
        }
    </style>
</head>
<body class="h-screen flex flex-col">

<!-- Header -->
<header class="bg-teal-600 text-white shadow-lg z-10">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="bg-white p-2 rounded-lg">
                <i class="fas fa-qrcode text-teal-600 text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold">POS Scanner</h1>
                <p class="text-teal-100 text-xs">Operator Mode</p>
            </div>
        </div>
        <a href="index.php?page=logout" class="text-teal-100 hover:text-white">
            <i class="fas fa-sign-out-alt text-xl"></i>
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="flex-1 flex flex-col md:flex-row max-w-7xl mx-auto w-full p-4 gap-4 overflow-hidden">
    
    <!-- Scanner Section -->
    <div class="w-full md:w-1/2 flex flex-col">
        <div class="bg-white rounded-2xl shadow-sm p-4 flex-1 flex flex-col">
            <div class="mb-4 flex justify-between items-center">
                <h2 class="font-bold text-gray-800">Kamera Pemindai</h2>
                <span class="text-xs bg-teal-100 text-teal-700 px-2 py-1 rounded-full animate-pulse">
                    <i class="fas fa-circle text-[8px] mr-1"></i> Aktif
                </span>
            </div>
            
            <div class="scan-area bg-gray-900 flex-1 relative flex items-center justify-center rounded-xl overflow-hidden">
                <div id="reader" class="w-full h-full"></div>
                <div class="absolute inset-0 border-2 border-teal-500 opacity-50 pointer-events-none rounded-xl z-10"></div>
                <!-- Scanning Animation Line -->
                <div class="absolute top-0 left-0 w-full h-1 bg-teal-400 shadow-[0_0_10px_#2dd4bf] animate-[scan_2s_infinite] z-20 pointer-events-none"></div>
            </div>
            
            <p class="text-center text-sm text-gray-500 mt-4">
                Arahkan kode QR tiket ke dalam kotak kamera.
            </p>
        </div>
    </div>

    <!-- Result Section -->
    <div class="w-full md:w-1/2 flex flex-col">
        <div class="bg-white rounded-2xl shadow-sm p-6 flex-1 flex flex-col justify-center items-center text-center" id="result-container">
            
            <!-- Default State -->
            <div id="default-state" class="flex flex-col items-center justify-center h-full">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6 text-gray-400">
                    <i class="fas fa-ticket-alt text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Menunggu Pemindaian</h3>
                <p class="text-gray-500 max-w-xs">
                    Hasil pemindaian tiket akan muncul secara otomatis di sini.
                </p>
            </div>

            <!-- Success State (Hidden by default) -->
            <div id="success-state" class="hidden w-full">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 text-green-500">
                    <i class="fas fa-check text-5xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-green-600 mb-1">Akses Diterima</h2>
                <p class="text-gray-500 mb-8" id="ticket-code-display">CODE-12345</p>

                <div class="bg-gray-50 rounded-xl p-6 w-full text-left space-y-4 border border-gray-100">
                    <div>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Jenis Tiket</span>
                        <p class="text-lg font-bold text-gray-800" id="ticket-type-display">Paket Wisata Premium</p>
                    </div>
                    <div class="flex justify-between">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Kunjungan</span>
                            <p class="font-medium text-gray-800" id="ticket-date-display">12 Jan 2024</p>
                        </div>
                        <div class="text-right">
                             <span class="text-xs text-gray-500 uppercase tracking-wide">Status</span>
                             <p class="font-medium text-green-600">Valid</p>
                        </div>
                    </div>
                </div>

                <button onclick="resetScanner()" class="mt-8 w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg hover:shadow-xl transform active:scale-95">
                    Scan Tiket Berikutnya
                </button>
            </div>

            <!-- Error State (Hidden by default) -->
            <div id="error-state" class="hidden w-full">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6 text-red-500 animate-bounce">
                    <i class="fas fa-times text-5xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-red-600 mb-2">Akses Ditolak</h2>
                <p class="text-gray-600 mb-8" id="error-message">Tiket tidak valid atau sudah digunakan.</p>
                
                <button onclick="resetScanner()" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg">
                    Coba Lagi
                </button>
            </div>

        </div>
    </div>
</main>

<style>
    @keyframes scan {
        0% { top: 0%; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
</style>

<script>
    let isScanning = true;
    const html5QrcodeScanner = new Html5Qrcode("reader");

    function onScanSuccess(decodedText, decodedResult) {
        if (!isScanning) return;
        isScanning = false;
        
        // Play beep sound
        // new Audio('assets/beep.mp3').play().catch(e => console.log('Audio play failed'));

        // Show loading state if needed, or direct API call
        fetch('api/scan_ticket.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ticket_code: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('default-state').classList.add('hidden');
            
            if (data.success) {
                document.getElementById('success-state').classList.remove('hidden');
                document.getElementById('error-state').classList.add('hidden');
                
                document.getElementById('ticket-code-display').innerText = decodedText;
                document.getElementById('ticket-type-display').innerText = data.ticket.name_en; // Or name_id based on preference
                document.getElementById('ticket-date-display').innerText = data.ticket.visit_date;
                
                // Visual feedback green flash
                document.body.style.backgroundColor = "#d1fae5";
                setTimeout(() => document.body.style.backgroundColor = "#f3f4f6", 500);

            } else {
                document.getElementById('success-state').classList.add('hidden');
                document.getElementById('error-state').classList.remove('hidden');
                document.getElementById('error-message').innerText = data.message;
                
                // Visual feedback red flash
                document.body.style.backgroundColor = "#fee2e2";
                setTimeout(() => document.body.style.backgroundColor = "#f3f4f6", 500);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan koneksi.');
            resetScanner();
        });
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
    }

    function resetScanner() {
        isScanning = true;
        document.getElementById('default-state').classList.remove('hidden');
        document.getElementById('success-state').classList.add('hidden');
        document.getElementById('error-state').classList.add('hidden');
        document.body.style.backgroundColor = "#f3f4f6";
    }

    html5QrcodeScanner.start(
        { facingMode: "environment" }, 
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        onScanSuccess,
        onScanFailure
    ).catch(err => {
        console.error("Error starting scanner", err);
        document.getElementById('reader').innerHTML = `<div class="text-white text-center p-4">Kamera tidak dapat diakses.<br>Pastikan izin kamera diberikan.</div>`;
    });
</script>

</body>
</html>
