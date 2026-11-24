<?php

use App\Http\Controllers\AegisController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login'));

Route::get('/login', [AegisController::class, 'showLogin'])->name('login');
Route::post('/login/verify-credentials', [AegisController::class, 'verifyCredentials'])->middleware('throttle:5,1');
Route::post('/login/complete-2fa', [AegisController::class, 'complete2fa']);
Route::post('/login/log-2fa-failed', [AegisController::class, 'log2faFailed']);

// Poin 1 (Confidentiality): Proteksi halaman dashboard dengan middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AegisController::class, 'dashboard'])->name('dashboard');
    
    // Menu Profil & Keamanan
    Route::get('/profil', [AegisController::class, 'profil'])->name('profil');
    Route::get('/update-password', [AegisController::class, 'showUpdatePassword'])->name('password.edit');
    Route::post('/update-password', [AegisController::class, 'updatePassword'])->name('password.update');
    Route::post('/enroll-face', [AegisController::class, 'enrollFace']);

    // Menu Mata Kuliah
    Route::get('/mata-kuliah', [AegisController::class, 'daftarMataKuliah'])->name('matkul.index');
    Route::get('/nilai-mata-kuliah', [AegisController::class, 'nilaiMataKuliah'])->name('matkul.nilai');

    // Menu Pembayaran SPP
    Route::get('/pembayaran-spp', [AegisController::class, 'pembayaranSpp'])->name('spp.index');
    Route::post('/pembayaran-spp', [AegisController::class, 'tambahTagihanSpp'])->name('spp.store');

    Route::post('/logout', [AegisController::class, 'logout'])->name('logout');
});