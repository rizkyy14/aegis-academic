@extends('layouts.student')
@section('title', 'Daftar Mata Kuliah')

@section('content')
<div class="bg-slate-900 text-white py-8 px-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-xl font-bold">Daftar Mata Kuliah</h1>
        <p class="text-xs text-slate-400 mt-1">Kurikulum Program Studi Manajemen Informatika.</p>
    </div>
</div>

<main class="max-w-5xl mx-auto px-6 py-8">
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="text-slate-500 bg-slate-100">
                <tr>
                    <th class="py-3 px-4 rounded-l-lg">Kode MK</th>
                    <th class="py-3 px-4">Nama Mata Kuliah</th>
                    <th class="py-3 px-4">SKS</th>
                    <th class="py-3 px-4 rounded-r-lg">Semester</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($matkul as $mk)
                <tr>
                    <td class="py-3 px-4 font-semibold text-indigo-600">{{ $mk->kode_mk }}</td>
                    <td class="py-3 px-4 font-medium text-slate-800">{{ $mk->nama_mk }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $mk->sks }} SKS</td>
                    <td class="py-3 px-4 text-slate-600">Semester {{ $mk->semester }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-slate-400">Belum ada data mata kuliah tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection