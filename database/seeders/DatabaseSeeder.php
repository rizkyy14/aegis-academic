<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MahasiswaProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'username' => 'admin_aegis',
            'email' => 'admin@polmed.ac.id',
            'password' => Hash::make('AdminAegis#2026'),
            'role' => 'admin',
        ]);

        // 2. Akun Dosen
        User::create([
            'username' => '198501012010121001',
            'email' => 'dosen.keamanan@polmed.ac.id',
            'password' => Hash::make('DosenPolmed#2026'),
            'role' => 'dosen',
        ]);

        // 3. Akun Mahasiswa + Profil Terenkripsi AES-256
        $mhs = User::create([
            'username' => '2405102001',
            'email' => 'rizky@students.polmed.ac.id',
            'password' => Hash::make('MhsAegis#2026'),
            'role' => 'mahasiswa',
        ]);

        MahasiswaProfile::create([
            'user_id' => $mhs->id,
            'nama_lengkap' => 'Muhammad Rizky Ramadhan',
            'prodi' => 'D3 Manajemen Informatika',
            'semester' => 5,
            'nik' => '1271011410060001',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Almamater No. 1, Padang Bulan, Medan',
        ]);
    }
}