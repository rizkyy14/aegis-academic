<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — AEGIS Portal Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @stack('head')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex flex-col justify-between">

    <div>
        <!-- NAVBAR UTAMA 2-TONE DENGAN HOVER DROPDOWN -->
        <nav class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-40">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                
                <!-- Kiri: Logo AEGIS + Menu Navigasi -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 bg-white/95 px-2.5 py-1 rounded-lg">
                        <img src="{{ asset('images/aegis-transparent.png') }}" alt="AEGIS" class="h-7 w-auto object-contain">
                    </a>

                    <!-- Menu Desktop -->
                    <div class="hidden md:flex items-center gap-1 text-xs font-semibold">
                        <!-- 1. Dashboard -->
                        <a href="{{ route('dashboard') }}"
                            class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Dashboard
                        </a>

                        <!-- 2. Dropdown Profil (Hover) -->
                        <div class="relative group">
                            <button type="button"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition {{ request()->routeIs('profil', 'password.edit') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                                <span>Profil</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Isi Dropdown Profil -->
                            <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 absolute left-0 pt-2 w-48 z-50">
                                <div class="bg-white text-slate-700 rounded-xl shadow-xl border border-slate-200/90 py-1.5 overflow-hidden">
                                    <a href="{{ route('profil') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Profil Mahasiswa
                                    </a>
                                    <a href="{{ route('password.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Update Password
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Dropdown Mata Kuliah (Hover) -->
                        <div class="relative group">
                            <button type="button"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition {{ request()->routeIs('matkul.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                                <span>Mata Kuliah</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Isi Dropdown Mata Kuliah -->
                            <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 absolute left-0 pt-2 w-52 z-50">
                                <div class="bg-white text-slate-700 rounded-xl shadow-xl border border-slate-200/90 py-1.5 overflow-hidden">
                                    <a href="{{ route('matkul.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Daftar Mata Kuliah
                                    </a>
                                    <a href="{{ route('matkul.nilai') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-medium hover:bg-indigo-50 hover:text-indigo-600 transition">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Nilai Mata Kuliah
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Pembayaran SPP -->
                        <a href="{{ route('spp.index') }}"
                            class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('spp.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                            Pembayaran SPP
                        </a>
                    </div>
                </div>

                <!-- Kanan: Badge Keamanan & Keluar -->
                <div class="flex items-center gap-3">
                    <span class="hidden lg:inline-flex items-center gap-1.5 text-[11px] text-emerald-300 bg-emerald-500/15 border border-emerald-400/30 px-3 py-1 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> {{ Auth::user()->username }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-semibold px-3.5 py-1.5 rounded-lg bg-slate-800 hover:bg-red-500/20 text-slate-200 hover:text-red-300 border border-slate-700 transition">
                            Keluar
                        </button>
                    </form>
                </div>

            </div>
        </nav>

        <!-- KONTEN HALAMAN -->
        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer class="py-3.5 text-center text-xs text-slate-500 border-t border-slate-200 bg-white">
        &copy; {{ date('Y') }} AEGIS — Politeknik Negeri Medan. All rights reserved.
    </footer>

    @stack('scripts')
</body>
</html>