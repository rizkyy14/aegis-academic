<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AegisController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    // TAHAP 1: Validasi Kredensial (AJAX JSON)
    public function verifyCredentials(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Pastikan tidak ada sesi aktif sebelumnya
        Auth::logout();
        $request->session()->forget('pending_2fa_user_id');

        $user = User::where('username', $request->username)->where('is_active', true)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            DB::table('audit_logs')->insert([
                'user_id' => $user?->id,
                'action' => 'LOGIN_FAILED (' . $request->username . ')',
                'target_table' => 'users',
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'NIM/NIP atau kata sandi tidak valid.'
            ], 401);
        }

        if (empty($user->face_descriptor)) {
            Auth::login($user);
            $request->session()->regenerate();

            DB::table('audit_logs')->insert([
                'user_id' => $user->id,
                'action' => 'LOGIN_SUCCESS (NO_2FA_YET)',
                'target_table' => 'users',
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'authenticated',
                'redirect' => '/dashboard'
            ]);
        }

        // Tahan user di Tahap 2 (BELUM Auth::login)
        $request->session()->put('pending_2fa_user_id', $user->id);

        return response()->json([
            'status' => 'require_2fa',
            'nama' => $user->profile?->nama_lengkap ?? $user->username,
            'descriptor' => json_decode($user->face_descriptor)
        ]);
    }

    // TAHAP 2: Finalisasi Login Setelah Wajah Cocok
    public function complete2fa(Request $request)
    {
        $userId = $request->session()->get('pending_2fa_user_id');

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Sesi 2FA kadaluarsa.'], 403);
        }

        $user = User::findOrFail($userId);
        $request->session()->forget('pending_2fa_user_id');

        Auth::login($user);
        $request->session()->regenerate();

        DB::table('audit_logs')->insert([
            'user_id' => $user->id,
            'action' => 'LOGIN_2FA_VERIFIED',
            'target_table' => 'users',
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'authenticated',
            'redirect' => '/dashboard'
        ]);
    }

    // Catat jika verifikasi wajah gagal / ditolak
    public function log2faFailed(Request $request)
    {
        $userId = $request->session()->get('pending_2fa_user_id');
        
        // Cabut sesi pending 2FA di server agar tidak bisa di-bypass
        $request->session()->forget('pending_2fa_user_id');

        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'action' => '2FA_FACE_REJECTED',
            'target_table' => 'users',
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['status' => 'rejected']);
    }

    // Dashboard Utama
    public function dashboard()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $logs = DB::table('audit_logs')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('user', 'profile', 'logs'));
    }

    // Simpan Vektor Wajah (Face Enrollment dari Dashboard)
    public function enrollFace(Request $request)
    {
        $request->validate([
            'descriptor' => ['required', 'array', 'size:128'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'face_descriptor' => json_encode($request->descriptor),
        ]);

        DB::table('audit_logs')->insert([
            'user_id' => $user->id,
            'action' => 'BIOMETRIC_FACE_ENROLLED',
            'target_table' => 'users',
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Struktur biometrik wajah berhasil didaftarkan!'
        ]);
    }

    // 1. Halaman Profil Mahasiswa (Dekripsi AES-256)
    public function profil()
    {
        $user = Auth::user();
        $profile = $user->profile;
        return view('student.profil', compact('user', 'profile'));
    }

    // 2. Halaman & Proses Update Password (Bcrypt Hashing + Audit Log)
    public function showUpdatePassword()
    {
        $user = Auth::user();
        return view('student.update-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        DB::table('audit_logs')->insert([
            'user_id' => $user->id,
            'action' => 'PASSWORD_UPDATED',
            'target_table' => 'users',
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password berhasil diperbarui dan dienkripsi ulang dengan Bcrypt!');
    }

    // 3. Halaman Daftar Mata Kuliah
    public function daftarMataKuliah()
    {
        $user = Auth::user();
        $matkul = DB::table('mata_kuliah')->orderBy('semester')->get();
        return view('student.mata-kuliah', compact('user', 'matkul'));
    }

    // 4. Halaman Nilai Mata Kuliah (dengan Validasi Integrity Hash SHA-256)
   // 4. Halaman Nilai Mata Kuliah (dengan Validasi Integrity Hash SHA-256)
    public function nilaiMataKuliah()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Tentukan kolom relasi mahasiswa di tabel krs secara dinamis
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('krs');
        $fkColumn = in_array('mahasiswa_id', $columns) ? 'mahasiswa_id'
            : (in_array('mahasiswa_profile_id', $columns) ? 'mahasiswa_profile_id' : 'user_id');

        $ownerId = ($fkColumn === 'user_id') ? $user->id : ($profile?->id ?? 0);

        $krs = DB::table('krs')
            ->join('mata_kuliah', 'krs.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('krs.' . $fkColumn, $ownerId)
            ->select(
                'mata_kuliah.kode_mk',
                'mata_kuliah.nama_mk',
                'mata_kuliah.sks',
                'mata_kuliah.semester',
                'krs.nilai_huruf',
                'krs.integrity_hash'
            )
            ->get();

        return view('student.nilai-mata-kuliah', compact('user', 'krs'));
    }

    // 5. Halaman Pembayaran SPP & Generator Virtual Account
    public function pembayaranSpp()
    {
        $user = Auth::user();
        $payments = DB::table('spp_payments')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('student.pembayaran-spp', compact('user', 'payments'));
    }

    public function tambahTagihanSpp(Request $request)
    {
        $request->validate([
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $user = Auth::user();

        // Cek apakah tagihan untuk semester tersebut sudah pernah dibuat
        $exists = DB::table('spp_payments')
            ->where('user_id', $user->id)
            ->where('semester', $request->semester)
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tagihan SPP untuk Semester ' . $request->semester . ' sudah terdaftar dalam riwayat.'
            ], 422);
        }

        // Generate Nomor VA Unik (Kode Institusi 88901 + NIM + Kode Semester)
        $vaNumber = '88901' . $user->username . '0' . $request->semester;
        $amount = 3500000;

        DB::table('spp_payments')->insert([
            'user_id' => $user->id,
            'semester' => $request->semester,
            'va_number' => $vaNumber,
            'amount' => $amount,
            'status' => 'BELUM LUNAS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('audit_logs')->insert([
            'user_id' => $user->id,
            'action' => 'SPP_BILL_CREATED (SEMESTER ' . $request->semester . ')',
            'target_table' => 'spp_payments',
            'ip_address' => $request->ip(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'va_number' => $vaNumber,
            'semester' => $request->semester,
            'amount' => number_format($amount, 0, ',', '.')
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}