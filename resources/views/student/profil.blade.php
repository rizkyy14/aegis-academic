@extends('layouts.student')
@section('title', 'Profil Mahasiswa')

@section('content')
<div class="bg-slate-900 text-white py-8 px-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-xl font-bold">Profil Mahasiswa</h1>
        <p class="text-xs text-slate-400 mt-1">Informasi biodata akademik dengan dekripsi otomatis AES-256-CBC.</p>
    </div>
</div>

<main class="max-w-5xl mx-auto px-6 py-8">
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-bold text-slate-900">{{ $profile->nama_lengkap ?? $user->username }}</h2>
                <p class="text-xs text-slate-500">{{ $profile->prodi ?? 'D3 Manajemen Informatika' }} • Semester {{ $profile->semester ?? 5 }}</p>
            </div>
            <span class="text-[11px] font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                Terdekripsi Aman (AES-256)
            </span>
        </div>

        @if($profile)
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 mt-5 text-sm">
            <div class="p-3.5 bg-slate-50 border border-slate-200/60 rounded-xl">
                <span class="text-xs text-slate-400 block">NIM</span>
                <span class="font-semibold text-slate-800">{{ $user->username }}</span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200/60 rounded-xl">
                <span class="text-xs text-slate-400 block">Email Institusi</span>
                <span class="font-semibold text-slate-800">{{ $user->email }}</span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200/60 rounded-xl">
                <span class="text-xs text-slate-400 block">NIK (AES-256 Decrypted)</span>
                <span class="font-semibold text-slate-800">{{ $profile->nik }}</span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200/60 rounded-xl">
                <span class="text-xs text-slate-400 block">No. Handphone (AES-256 Decrypted)</span>
                <span class="font-semibold text-slate-800">{{ $profile->no_hp }}</span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200/60 rounded-xl sm:col-span-2">
                <span class="text-xs text-slate-400 block">Alamat Domisili (AES-256 Decrypted)</span>
                <span class="font-semibold text-slate-800">{{ $profile->alamat }}</span>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection