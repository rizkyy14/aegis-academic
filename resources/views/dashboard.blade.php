@extends('layouts.student')
@section('title', 'Dashboard Mahasiswa')

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
@endpush

@section('content')
    <!-- Hero Header Navy -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-900 to-indigo-950 text-white pb-24 pt-8 px-6">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-indigo-300 text-xs font-semibold uppercase tracking-wider">Tahun Akademik 2026/2027 • Semester Ganjil</p>
                <h1 class="text-2xl font-bold text-white mt-1">Dashboard Akademik Mahasiswa</h1>
                <p class="text-slate-300 text-xs mt-1">Politeknik Negeri Medan • Sistem Informasi Akademik Terpadu</p>
            </div>
            <div class="bg-slate-800/80 border border-slate-700 rounded-2xl px-4 py-3 text-left sm:text-right">
                <span class="text-[11px] text-slate-400 block">Status Keamanan Akun</span>
                <span class="text-xs font-bold text-emerald-400 flex items-center sm:justify-end gap-1.5 mt-0.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    {{ $user->face_descriptor ? '2FA Biometrik Aktif' : 'Kredensial Terverifikasi' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Konten Utama (Overlapping ke Atas Hero Navy) -->
    <main class="max-w-6xl mx-auto px-6 -mt-16 space-y-6 pb-10">

        <!-- KARTU IDENTITAS MAHASISWA (Foto + Data Diri Utama) -->
        <div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-7 shadow-lg shadow-slate-200/60">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                
                <!-- Foto Mahasiswa (Otomatis fallback ke avatar inisial jika foto belum ada) -->
                <div class="shrink-0 relative">
                    <div class="w-28 h-32 sm:w-32 sm:h-36 rounded-2xl overflow-hidden bg-slate-100 border-2 border-indigo-100 shadow-sm flex items-center justify-center">
                        <img src="{{ asset('images/foto-mahasiswa.jpg') }}" alt="Foto Mahasiswa"
                            onerror="this.style.display='none'; document.getElementById('avatar-fallback').classList.remove('hidden');"
                            class="w-full h-full object-cover">
                        
                        <div id="avatar-fallback" class="hidden w-full h-full bg-gradient-to-br from-indigo-600 to-slate-800 text-white flex flex-col items-center justify-center p-2 text-center">
                            <span class="text-2xl font-bold tracking-wider">
                                {{ strtoupper(substr($profile->nama_lengkap ?? $user->username, 0, 2)) }}
                            </span>
                            <span class="text-[10px] text-indigo-200 mt-1">Foto Profil</span>
                        </div>
                    </div>
                    <span class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 whitespace-nowrap px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white shadow">
                        Mahasiswa Aktif
                    </span>
                </div>

                <!-- Data Diri Mahasiswa -->
                <div class="flex-1 w-full text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $profile->nama_lengkap ?? 'Muhammad Rizky Ramadhan' }}</h2>
                            <p class="text-xs font-semibold text-indigo-600 mt-0.5">{{ $profile->prodi ?? 'D3 Manajemen Informatika' }} • Semester {{ $profile->semester ?? 5 }}</p>
                        </div>
                        <span class="inline-flex items-center justify-center gap-1.5 text-[11px] font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600">
                            NIM: <strong class="text-slate-900">{{ $user->username }}</strong>
                        </span>
                    </div>

                    <!-- Grid Detail: Nama, NIM, Hari/Tanggal Lahir, Prodi, Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 mt-4 text-left text-xs">
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                            <span class="text-slate-400 block text-[11px]">Nama Lengkap</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $profile->nama_lengkap ?? 'Muhammad Rizky Ramadhan' }}</span>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                            <span class="text-slate-400 block text-[11px]">Nomor Induk Mahasiswa (NIM)</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $user->username }}</span>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                            <span class="text-slate-400 block text-[11px]">Hari, Tanggal Lahir</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">
                                @if(!empty($profile->tanggal_lahir))
                                    {{ \Carbon\Carbon::parse($profile->tanggal_lahir)->locale('id')->translatedFormat('l, d F Y') }}
                                @else
                                    Sabtu, 14 Oktober 2006
                                @endif
                            </span>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                            <span class="text-slate-400 block text-[11px]">Program Studi</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $profile->prodi ?? 'D3 Manajemen Informatika' }}</span>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 sm:col-span-2">
                            <span class="text-slate-400 block text-[11px]">Email Institusi</span>
                            <span class="font-semibold text-slate-800 mt-0.5 block">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Menu Cepat Akademik & Biometrik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('profil') }}" class="bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm hover:border-indigo-400 transition">
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">Biodata Lengkap</span>
                <h3 class="font-bold text-slate-900 mt-3">Profil Mahasiswa</h3>
                <p class="text-xs text-slate-500 mt-1">Lihat NIK, No. HP & Alamat (AES-256)</p>
            </a>

            <a href="{{ route('matkul.nilai') }}" class="bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm hover:border-indigo-400 transition">
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg">Akademik</span>
                <h3 class="font-bold text-slate-900 mt-3">Nilai Mata Kuliah</h3>
                <p class="text-xs text-slate-500 mt-1">Validasi integritas SHA-256</p>
            </a>

            <a href="{{ route('spp.index') }}" class="bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm hover:border-indigo-400 transition">
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">Keuangan</span>
                <h3 class="font-bold text-slate-900 mt-3">Pembayaran SPP</h3>
                <p class="text-xs text-slate-500 mt-1">Buat tagihan & nomor Virtual Account</p>
            </a>

            <div onclick="openEnrollModal()" class="cursor-pointer bg-white border border-slate-200/90 p-5 rounded-2xl shadow-sm hover:border-indigo-400 transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg">Biometrik</span>
                    <span class="text-xs font-semibold text-indigo-600">Atur &rarr;</span>
                </div>
                <h3 class="font-bold text-slate-900 mt-3">Verifikasi Wajah</h3>
                <p class="text-xs text-slate-500 mt-1">
                    {{ $user->face_descriptor ? '2FA Wajah Aktif • Klik perbarui' : 'Klik untuk daftarkan wajah 2FA' }}
                </p>
            </div>
        </div>

        <!-- Riwayat Aktivitas Login (Audit Log) -->
        {{-- <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-3">Riwayat Aktivitas Keamanan Akun</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="text-slate-500 bg-slate-100">
                        <tr>
                            <th class="py-2.5 px-3 rounded-l-lg">Waktu</th>
                            <th class="py-2.5 px-3">Aktivitas</th>
                            <th class="py-2.5 px-3 rounded-r-lg">Alamat IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($logs as $log)
                        <tr>
                            <td class="py-2.5 px-3 text-slate-600">{{ $log->created_at }}</td>
                            <td class="py-2.5 px-3 font-medium text-indigo-600">{{ $log->action }}</td>
                            <td class="py-2.5 px-3 text-slate-500">{{ $log->ip_address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}
    </main>

    <!-- Modal Enrollment Wajah -->
    <div id="enroll-modal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl">
            <h3 class="text-base font-bold text-slate-900">Pendaftaran Biometrik Wajah</h3>
            <p class="text-xs text-slate-500 mt-1 mb-4">Lepas kacamata & pastikan cahaya terang agar akurat.</p>
            <div class="w-60 h-44 mx-auto rounded-2xl overflow-hidden bg-slate-900 border-2 border-indigo-500 mb-4">
                <video id="enroll-video" autoplay muted playsinline class="w-full h-full object-cover transform scale-x-[-1]"></video>
            </div>
            <p id="enroll-status" class="text-xs font-medium text-indigo-600 mb-4">Menyiapkan kamera...</p>
            <div class="flex gap-2">
                <button type="button" onclick="closeEnrollModal()" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Tutup</button>
                <button type="button" id="btn-capture-face" disabled class="flex-1 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold disabled:opacity-50 hover:bg-indigo-700">Simpan Wajah</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let enrollStream = null;
    async function openEnrollModal() {
        document.getElementById('enroll-modal').classList.remove('hidden');
        const status = document.getElementById('enroll-status');
        const btn = document.getElementById('btn-capture-face');
        const video = document.getElementById('enroll-video');
        status.textContent = 'Memuat model AI...';
        await Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/models')
        ]);
        enrollStream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = enrollStream;
        status.textContent = 'Kamera aktif. Klik "Simpan Wajah".';
        btn.disabled = false;

        btn.onclick = async () => {
            btn.disabled = true;
            status.textContent = 'Mengekstrak 128-d vektor wajah...';
            const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
            if (!detection) {
                status.textContent = 'Wajah tidak terdeteksi! Coba lagi.';
                btn.disabled = false;
                return;
            }
            const res = await fetch('/enroll-face', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ descriptor: Array.from(detection.descriptor) })
            });
            const data = await res.json();
            if (data.status === 'success') {
                alert(data.message);
                closeEnrollModal();
                location.reload();
            }
        };
    }
    function closeEnrollModal() {
        if (enrollStream) enrollStream.getTracks().forEach(t => t.stop());
        document.getElementById('enroll-modal').classList.add('hidden');
    }
</script>
@endpush