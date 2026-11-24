@extends('layouts.student')
@section('title', 'Pembayaran SPP')

@section('content')
<div class="bg-slate-900 text-white py-8 px-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-xl font-bold">Pembayaran SPP & Virtual Account</h1>
        <p class="text-xs text-slate-400 mt-1">Pembuatan tagihan UKT/SPP mahasiswa dan pemantauan riwayat transaksi.</p>
    </div>
</div>

<main class="max-w-5xl mx-auto px-6 py-8 space-y-6">

    <!-- 1. Tutorial Menambah Tagihan & Form Buat Tagihan -->
    <div class="grid md:grid-cols-12 gap-6">
        
        <!-- Panel Tutorial Singkat (7 Kolom) -->
        <div class="md:col-span-7 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-2.5 mb-3">
                <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">?</span>
                <h2 class="text-sm font-bold text-slate-900">Tutorial Menambah Tagihan SPP</h2>
            </div>
            <ol class="list-decimal list-inside space-y-2 text-xs text-slate-600 leading-relaxed">
                <li>Pilih <strong>Semester</strong> yang akan dibayarkan pada menu dropdown di sebelah kanan.</li>
                <li>Klik tombol <strong>Tambah Tagihan</strong> untuk menerbitkan nomor Virtual Account (VA) baru.</li>
                <li>Salin <strong>Nomor Virtual Account</strong> yang muncul pada jendela pop-up konfirmasi.</li>
                <li>Lakukan pembayaran melalui teller bank mitra atau mobile banking menggunakan nomor VA tersebut.</li>
                <li>Status tagihan dapat dipantau pada tabel <strong>Riwayat Pembayaran</strong> di bagian bawah halaman ini.</li>
            </ol>
        </div>

        <!-- Panel Form Tambah Tagihan (5 Kolom) -->
        <div class="md:col-span-5 bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 mb-1">Buat Tagihan Baru</h2>
                <p class="text-xs text-slate-500 mb-4">Tarif UKT/SPP per semester: <strong class="text-slate-800">Rp 3.500.000</strong></p>

                <div id="spp-error" class="hidden mb-3 p-3 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs font-medium"></div>

                <form id="form-spp" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Semester</label>
                        <select id="semester-select" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-indigo-500">
                            <option value="">-- Pilih Semester --</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" id="btn-tambah-spp"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs sm:text-sm shadow-md shadow-indigo-200 transition">
                        + Tambah Tagihan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Tabel Riwayat Pembayaran -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 mb-4">Riwayat Pembayaran SPP</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="text-slate-500 bg-slate-100">
                    <tr>
                        <th class="py-3 px-4 rounded-l-lg">Semester</th>
                        <th class="py-3 px-4">Nomor Virtual Account (VA)</th>
                        <th class="py-3 px-4">Nominal Tagihan</th>
                        <th class="py-3 px-4">Tanggal Dibuat</th>
                        <th class="py-3 px-4 rounded-r-lg">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($payments as $pay)
                    <tr>
                        <td class="py-3 px-4 font-semibold text-slate-800">Semester {{ $pay->semester }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-indigo-600">{{ $pay->va_number }}</td>
                        <td class="py-3 px-4 text-slate-700">Rp {{ number_format($pay->amount, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ \Carbon\Carbon::parse($pay->created_at)->format('d M Y, H:i') }}</td>
                        <td class="py-3 px-4">
                            @if($pay->status === 'LUNAS')
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold text-[11px]">LUNAS</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-semibold text-[11px]">BELUM LUNAS</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">Belum ada riwayat tagihan atau pembayaran SPP.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- 3. POPUP MODAL NOMOR VIRTUAL ACCOUNT -->
<div id="va-modal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-200">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h3 class="text-base font-bold text-slate-900">Tagihan Berhasil Dibuat!</h3>
        <p id="va-modal-desc" class="text-xs text-slate-500 mt-1">Gunakan nomor Virtual Account di bawah ini untuk menyelesaikan pembayaran SPP.</p>

        <div class="my-4 p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <span class="text-[11px] uppercase tracking-wider text-slate-400 block">Nomor Virtual Account</span>
            <span id="va-number-display" class="text-lg font-mono font-bold text-indigo-600 tracking-wider select-all mt-1 block"></span>
            <span id="va-amount-display" class="text-xs font-semibold text-slate-700 mt-1 block"></span>
        </div>

        <div class="flex gap-2">
            <button type="button" onclick="copyVaNumber()" id="btn-copy-va"
                class="flex-1 py-2.5 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100 transition">
                Salin Nomor VA
            </button>
            <button type="button" onclick="location.reload()"
                class="flex-1 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition">
                Selesai
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.getElementById('form-spp').addEventListener('submit', async (e) => {
        e.preventDefault();
        const semester = document.getElementById('semester-select').value;
        const errBox = document.getElementById('spp-error');
        const btn = document.getElementById('btn-tambah-spp');

        if (!semester) return;

        btn.disabled = true;
        btn.textContent = 'Memproses tagihan...';
        errBox.classList.add('hidden');

        try {
            const res = await fetch("{{ route('spp.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ semester: semester })
            });

            const data = await res.json();

            if (!res.ok) {
                errBox.textContent = data.message || 'Gagal membuat tagihan.';
                errBox.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = '+ Tambah Tagihan';
                return;
            }

            // Tampilkan Pop-up Nomor VA
            document.getElementById('va-modal-desc').textContent = `Tagihan SPP Semester ${data.semester} berhasil diterbitkan.`;
            document.getElementById('va-number-display').textContent = data.va_number;
            document.getElementById('va-amount-display').textContent = `Total: Rp ${data.amount}`;
            document.getElementById('va-modal').classList.remove('hidden');

        } catch (err) {
            errBox.textContent = 'Terjadi kesalahan jaringan.';
            errBox.classList.remove('hidden');
            btn.disabled = false;
            btn.textContent = '+ Tambah Tagihan';
        }
    });

    function copyVaNumber() {
        const vaText = document.getElementById('va-number-display').textContent;
        navigator.clipboard.writeText(vaText);
        const copyBtn = document.getElementById('btn-copy-va');
        copyBtn.textContent = 'Tersalin!';
        setTimeout(() => copyBtn.textContent = 'Salin Nomor VA', 2000);
    }
</script>
@endpush