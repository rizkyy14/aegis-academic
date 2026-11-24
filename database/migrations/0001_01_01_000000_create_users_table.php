<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Users (RBAC + Password Hashing + Biometric Face Descriptor)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique(); // NIM / NIDN / NIP
            $table->string('email', 100)->unique();
            $table->string('password'); // Poin 5: Bcrypt / Argon2id Hash
            $table->enum('role', ['admin', 'dosen', 'mahasiswa'])->default('mahasiswa'); // Poin 1: Confidentiality (RBAC)
            $table->text('face_descriptor')->nullable(); // Poin 4: Biometric Vector (128-d float JSON)
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. Tabel Profil Mahasiswa (Data Sensitif Terenkripsi AES-256)
        Schema::create('mahasiswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap', 100);
            $table->string('prodi', 100)->default('D3 Manajemen Informatika');
            $table->integer('semester')->default(5);
            // Poin 5: Kolom terenkripsi menggunakan AES-256-CBC via Laravel Crypt
            $table->text('nik_encrypted');
            $table->text('no_hp_encrypted');
            $table->text('alamat_encrypted');
            $table->timestamps();
        });

        // 3. Tabel Mata Kuliah
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 20)->unique();
            $table->string('nama_mk', 100);
            $table->integer('sks');
            $table->integer('semester');
            $table->foreignId('dosen_id')->constrained('users');
            $table->timestamps();
        });

        // 4. Tabel KRS & Nilai (Dengan Integritas Checksum SHA-256)
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->onDelete('cascade');
            $table->string('tahun_akademik', 20);
            $table->enum('status', ['pending', 'disetujui'])->default('pending');
            $table->decimal('nilai_angka', 5, 2)->nullable();
            $table->string('nilai_huruf', 2)->nullable();
            // Poin 2: Integrity (SHA-256 HMAC Checksum untuk deteksi tampering nilai)
            $table->string('integrity_hash', 64)->nullable();
            $table->timestamps();
        });

        // 5. Tabel Audit Logs (Poin 2: Integrity & Non-repudiation)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 100); // LOGIN_SUCCESS, LOGIN_FAILED, UPDATE_NILAI, dll
            $table->string('target_table', 50)->nullable();
            $table->jsonb('old_data')->nullable();
            $table->jsonb('new_data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // Tabel bawaan session Laravel (Wajib ada karena SESSION_DRIVER=database)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('krs');
        Schema::dropIfExists('mata_kuliah');
        Schema::dropIfExists('mahasiswa_profiles');
        Schema::dropIfExists('users');
    }
};