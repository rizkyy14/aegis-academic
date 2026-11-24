<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AEGIS — Portal Akademik Politeknik Negeri Medan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col justify-between">

    <!-- 1. NAVBAR 2-TONE -->
    <header class="bg-slate-900 border-b border-slate-800 px-6 py-3.5 text-white">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <!-- Kiri: Nama Portal (AEGIS) -->
            <a href="/login" class="flex ">
                <img src="{{ asset('images/aegis-panjang.png') }}" alt="AEGIS" class="h-7 invert w-auto object-contain">
            </a>

            <!-- Kanan: Link Website Polmed -->
            <a href="https://polmed.ac.id" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-300 hover:text-white transition">
                <span>Website Polmed</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
    </header>

    <!-- 2. KONTEN UTAMA: 2-TONE CONTAINER -->
    <main class="flex-1 flex items-center justify-center px-4 sm:px-6 py-8">
        <div class="w-full max-w-5xl grid lg:grid-cols-12 rounded-3xl overflow-hidden shadow-2xl shadow-slate-300/60 border border-slate-200/80">

            <!-- TONE 1 (KIRI - 6 Kolom): Deep Navy Institutional Panel -->
            <div class="lg:col-span-6 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950 text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
                <!-- Dekorasi Cahaya Halus -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 space-y-6">
                    <!-- Logo & Nama Institusi Polmed -->
                    <div class="flex items-center gap-4">
                        <div class="bg-white p-2 rounded-2xl shadow-md shrink-0">
                            <img src="{{ asset('images/logo-polmed.png') }}" alt="Logo Politeknik Negeri Medan"
                                onerror="this.style.display='none'; document.getElementById('polmed-fallback').classList.remove('hidden');"
                                class="h-12 w-12 object-contain">
                            <div id="polmed-fallback" class="hidden w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg">
                                PM
                            </div>
                        </div>

                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-widest text-indigo-400">Sistem Informasi Akademik</span>
                            <h1 class="text-lg sm:text-xl font-bold text-white leading-tight">Politeknik Negeri Medan</h1>
                        </div>
                    </div>

                    <!-- Ucapan Selamat Datang -->
                    <div class="space-y-2.5 pt-2">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                            Academic Encrypted & Guarded Information System
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                            Layanan akademik terpadu yang dilengkapi enkripsi data sensitif dan autentikasi biometrik wajah untuk menjamin keamanan informasi mahasiswa, dosen, dan staf.
                        </p>
                    </div>

                    <!-- Alert Instruksi Login (Kontras di atas Dark Navy) -->
                    <div class="p-4 rounded-2xl bg-indigo-500/15 border border-indigo-400/30 flex items-start gap-3">
                        <svg class="w-5 h-5 text-indigo-300 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-indigo-100 font-medium leading-relaxed">
                            Harap lakukan login dengan user ID (NIM/NIP/ID) dan password untuk melanjutkan.
                        </p>
                    </div>
                </div>

            </div>

            <!-- TONE 2 (KANAN - 6 Kolom): Clean White Form Panel -->
            <div class="lg:col-span-6 bg-white p-8 sm:p-10 flex flex-col justify-center">
                
                <!-- Stepper Indicator 2FA -->
                <div class="flex items-center justify-center gap-2 mb-6 text-xs font-semibold">
                    <span id="badge-step-1" class="px-3 py-1 rounded-full bg-indigo-600 text-white transition">
                        1. Kredensial
                    </span>
                    <span class="w-6 h-0.5 bg-slate-200"></span>
                    <span id="badge-step-2" class="px-3 py-1 rounded-full bg-slate-100 text-slate-400 transition">
                        2. Verifikasi Wajah
                    </span>
                </div>

                <!-- Kotak Alert Status Error / Sukses -->
                <div id="alert-box" class="hidden mb-4 p-3.5 rounded-xl text-xs font-medium"></div>

                <!-- STEP 1: FORM KREDENSIAL -->
                <form id="form-step-1" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">User ID (NIM / NIP / ID)</label>
                        <input type="text" id="username" value="2405102001" required
                            placeholder="Masukkan NIM / NIP / ID"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
                        <div class="relative">
                            <input type="password" id="password" value="MhsAegis#2026" required
                                placeholder="••••••••"
                                class="w-full pl-4 pr-11 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                            
                            <button type="button" onclick="togglePassword()" aria-label="Tampilkan atau sembunyikan kata sandi"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-indigo-600 transition focus:outline-none">
                                <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="btn-step-1"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-indigo-200 transition">
                        Masuk
                    </button>
                </form>

                <!-- STEP 2: PANEL WEBCAM FACE RECOGNITION -->
                <div id="panel-step-2" class="hidden text-center space-y-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Verifikasi Wajah Diperlukan</h3>
                        <p id="welcome-2fa-text" class="text-xs text-slate-500 mt-0.5">Posisikan wajah Anda di depan kamera.</p>
                    </div>

                    <div class="relative w-64 h-48 mx-auto rounded-2xl overflow-hidden bg-slate-900 border-2 border-indigo-500 shadow-inner">
                        <video id="webcam" autoplay muted playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
                        <canvas id="overlay" class="absolute inset-0 w-full h-full transform scale-x-[-1]"></canvas>
                    </div>

                    <p id="scan-status" class="text-xs font-medium text-indigo-600 animate-pulse">
                        Memuat model biometrik...
                    </p>

                    <button type="button" onclick="location.reload()" class="text-xs text-slate-400 hover:text-slate-600 underline">
                        Batal & Kembali ke Login
                    </button>
                </div>

                <!-- Indikator Keamanan Ringkas -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-center gap-2 text-[11px] text-slate-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Autentikasi 2 Faktor</span>
                </div>
            </div>

        </div>
    </main>

    <!-- 3. FOOTER COPYRIGHT SAJA -->
    <footer class="py-3.5 text-center text-xs text-slate-500 border-t border-slate-200 bg-white">
        &copy; {{ date('Y') }} AEGIS — Politeknik Negeri Medan. All rights reserved.
    </footer>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const alertBox = document.getElementById('alert-box');
        let storedDescriptor = null;
        let scanInterval = null;
        let modelsLoaded = false;

        // Preload model AI di latar belakang
        window.addEventListener('DOMContentLoaded', async () => {
            try {
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                ]);
                modelsLoaded = true;
            } catch (e) {
                console.error('Gagal preload model:', e);
            }
        });

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        function showAlert(message, type = 'error') {
            alertBox.classList.remove('hidden', 'bg-red-50', 'text-red-600', 'border-red-200', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
            if (type === 'error') {
                alertBox.classList.add('bg-red-50', 'text-red-600', 'border', 'border-red-200');
            } else {
                alertBox.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200');
            }
            alertBox.textContent = message;
        }

        // Handle Submit Tahap 1
        document.getElementById('form-step-1').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-step-1');
            btn.disabled = true;
            btn.textContent = 'Memeriksa kredensial...';
            alertBox.classList.add('hidden');

            try {
                const response = await fetch('/login/verify-credentials', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        username: document.getElementById('username').value,
                        password: document.getElementById('password').value
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    showAlert(data.message || 'Terjadi kesalahan atau terlalu banyak percobaan.');
                    btn.disabled = false;
                    btn.textContent = 'Masuk';
                    return;
                }

                if (data.status === 'authenticated') {
                    window.location.href = data.redirect;
                    return;
                }

                if (data.status === 'require_2fa') {
                    storedDescriptor = new Float32Array(data.descriptor);
                    document.getElementById('welcome-2fa-text').textContent = `Halo ${data.nama}, hadapkan wajah ke kamera.`;
                    
                    document.getElementById('form-step-1').classList.add('hidden');
                    document.getElementById('panel-step-2').classList.remove('hidden');
                    
                    document.getElementById('badge-step-1').classList.replace('bg-indigo-600', 'bg-emerald-500');
                    document.getElementById('badge-step-2').classList.replace('bg-slate-100', 'bg-indigo-600');
                    document.getElementById('badge-step-2').classList.replace('text-slate-400', 'text-white');

                    startFaceVerification();
                }
            } catch (err) {
                showAlert('Gagal terhubung ke server.');
                btn.disabled = false;
                btn.textContent = 'Masuk';
            }
        });

        // Jalankan Pemindaian Wajah di Tahap 2
        async function startFaceVerification() {
            const statusText = document.getElementById('scan-status');
            const video = document.getElementById('webcam');

            try {
                if (!modelsLoaded) {
                    statusText.textContent = 'Menyiapkan model biometrik...';
                    await Promise.all([
                        faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                        faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                    ]);
                }

                statusText.textContent = 'Menyalakan kamera...';
                const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } });
                video.srcObject = stream;

                const detectorOptions = new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.6 });

                video.addEventListener('play', () => {
                    statusText.textContent = 'Memindai wajah...';
                    let mismatchCount = 0;
                    const maxAttempts = 8;
                    let isFinished = false;

                    scanInterval = setInterval(async () => {
                        if (isFinished) return;

                        const detection = await faceapi
                            .detectSingleFace(video, detectorOptions)
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        if (detection && !isFinished) {
                            const distance = faceapi.euclideanDistance(detection.descriptor, storedDescriptor);

                            if (distance < 0.43) {
                                isFinished = true;
                                clearInterval(scanInterval);
                                stream.getTracks().forEach(track => track.stop());
                                
                                statusText.classList.remove('animate-pulse', 'text-indigo-600');
                                statusText.classList.add('text-emerald-600', 'font-bold');
                                statusText.textContent = `Wajah Terverifikasi! (Skor jarak: ${distance.toFixed(2)})`;

                                const res = await fetch('/login/complete-2fa', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                                });
                                const finalData = await res.json();
                                if (finalData.status === 'authenticated') {
                                    window.location.href = finalData.redirect;
                                }
                            } else {
                                mismatchCount++;
                                statusText.textContent = `Wajah tidak cocok (${mismatchCount}/${maxAttempts}) — Jarak: ${distance.toFixed(2)}`;

                                if (mismatchCount >= maxAttempts) {
                                    isFinished = true;
                                    clearInterval(scanInterval);
                                    stream.getTracks().forEach(track => track.stop());

                                    statusText.classList.remove('animate-pulse', 'text-indigo-600');
                                    statusText.classList.add('text-red-600', 'font-bold');
                                    statusText.textContent = 'Verifikasi Biometrik Ditolak.';

                                    await fetch('/login/log-2fa-failed', {
                                        method: 'POST',
                                        headers: { 'X-CSRF-TOKEN': csrfToken }
                                    });

                                    showAlert('Akses Ditolak: Wajah tidak cocok dengan pemilik akun. Silakan ulangi login.');
                                }
                            }
                        }
                    }, 500);
                });
            } catch (err) {
                statusText.textContent = 'Gagal mengakses kamera. Pastikan izin kamera aktif.';
            }
        }
    </script>
</body>
</html>