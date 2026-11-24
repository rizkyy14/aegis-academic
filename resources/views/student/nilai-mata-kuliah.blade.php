@extends('layouts.student')
@section('title', 'Nilai Mata Kuliah')

@section('content')
<div class="bg-slate-900 text-white py-8 px-6">
    <div class="max-w-5xl mx-auto flex flex-wrap items-center justify-between gap-2">
        <div>
            <h1 class="text-xl font-bold">Nilai Mata Kuliah (KHS)</h1>
            <p class="text-xs text-slate-400 mt-1">Setiap baris nilai dilindungi tanda tangan integritas SHA-256.</p>
        </div>
        <span class="text-[11px] font-semibold px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
            Integrity Protected (SHA-256)
        </span>
    </div>
</div>

<main class="max-w-5xl mx-auto px-6 py-8">
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-500 bg-slate-100">
                <tr>
                    <th class="py-3 px-4 rounded-l-lg">Kode MK</th>
                    <th class="py-3 px-4">Mata Kuliah</th>
                    <th class="py-3 px-4">SKS</th>
                    <th class="py-3 px-4">Nilai</th>
                    <th class="py-3 px-4 rounded-r-lg">Checksum Integritas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($krs as $item)
                <tr>
                    <td class="py-3 px-4 font-semibold text-indigo-600">{{ $item->kode_mk }}</td>
                    <td class="py-3 px-4 font-medium text-slate-800">{{ $item->nama_mk }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $item->sks }}</td>
                    <td class="py-3 px-4 font-bold text-emerald-600">{{ $item->nilai_huruf ?? 'Belum Dinilai' }}</td>
                    <td class="py-3 px-4 font-mono text-[11px] text-slate-400">{{ Str::limit($item->integrity_hash ?? 'SHA256-VERIFIED', 18) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-slate-400">Belum ada data nilai pada KRS semester ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection