@extends('layouts.student')
@section('title', 'Update Password')

@section('content')
<div class="bg-slate-900 text-white py-8 px-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-xl font-bold">Update Password</h1>
        <p class="text-xs text-slate-400 mt-1">Kata sandi baru akan otomatis di-hash satu arah menggunakan algoritma Bcrypt.</p>
    </div>
</div>

<main class="max-w-xl mx-auto px-6 py-8">
    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 shadow-sm">
        @if(session('success'))
            <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-600 text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru (Minimal 8 karakter)</label>
                <input type="password" name="new_password" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-indigo-500">
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-indigo-200 transition">
                Simpan Password Baru
            </button>
        </form>
    </div>
</main>
@endsection