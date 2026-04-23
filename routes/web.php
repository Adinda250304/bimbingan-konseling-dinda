<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WaliKelasController;

// ===== PUBLIC ROUTES =====
Route::get('/', [AuthController::class, 'showSplash'])->name('splash');
Route::get('/welcome', [AuthController::class, 'showWelcome'])->name('welcome');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kalender Guru BK
    Route::get('/kalender', [AdminController::class, 'kalender'])->name('kalender');
    Route::get('/api/kalender', [AdminController::class, 'apiKalender'])->name('api.kalender');
    Route::post('/api/kalender', [AdminController::class, 'apiKalenderStore'])->name('api.kalender.store');
    Route::put('/api/kalender/{kalender}', [AdminController::class, 'apiKalenderUpdate'])->name('api.kalender.update');
    Route::delete('/api/kalender/{kalender}', [AdminController::class, 'apiKalenderDestroy'])->name('api.kalender.destroy');


    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Jadwal
    Route::get('/jadwal', [AdminController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal/konseling', [AdminController::class, 'storeKonseling'])->name('jadwal.konseling.store');
    Route::post('/jadwal', [AdminController::class, 'storeJadwal'])->name('jadwal.store');
    Route::put('/jadwal/{jadwal}', [AdminController::class, 'updateJadwal'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [AdminController::class, 'deleteJadwal'])->name('jadwal.delete');

    // Riwayat & Hasil
    Route::get('/riwayat', [AdminController::class, 'riwayat'])->name('riwayat');
    Route::get('/konseling/{konseling}', [AdminController::class, 'showKonseling'])->name('konseling.show');
    Route::put('/konseling/{konseling}', [AdminController::class, 'updateKonseling'])->name('konseling.update');
    Route::put('/konseling/{konseling}/status', [AdminController::class, 'updateStatusKonseling'])->name('konseling.status');
    Route::post('/konseling/{konseling}/advance', [AdminController::class, 'advanceStatus'])->name('konseling.advance');
    Route::post('/konseling/{konseling}/setujui', [AdminController::class, 'setujuiPengajuan'])->name('konseling.setujui');
    Route::post('/konseling/{konseling}/tolak', [AdminController::class, 'tolakPengajuan'])->name('konseling.tolak');
    Route::delete('/konseling/{konseling}', [AdminController::class, 'deleteKonseling'])->name('konseling.delete');
    Route::get('/konseling/{konseling}/hasil', [AdminController::class, 'hasilKonseling'])->name('konseling.hasil');
    Route::post('/konseling/{konseling}/hasil', [AdminController::class, 'storeHasil'])->name('konseling.hasil.store');

    // Tindak Lanjut
    Route::post('/konseling/{konseling}/tindak-lanjut', [\App\Http\Controllers\TindakLanjutController::class, 'store'])->name('konseling.tindak-lanjut.store');
    Route::get('/tindak-lanjut/{tindakLanjut}/pdf', [\App\Http\Controllers\TindakLanjutController::class, 'pdf'])->name('tindak-lanjut.pdf');
    Route::post('/tindak-lanjut/{tindakLanjut}/wa', [\App\Http\Controllers\TindakLanjutController::class, 'kirimWa'])->name('tindak-lanjut.wa');
    Route::post('/tindak-lanjut/{tindakLanjut}/email', [\App\Http\Controllers\TindakLanjutController::class, 'kirimEmail'])->name('tindak-lanjut.email');
});

// ===== SISWA ROUTES =====
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [SiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/pengajuan', [SiswaController::class, 'pengajuan'])->name('pengajuan');
    Route::post('/pengajuan', [SiswaController::class, 'storePengajuan'])->name('pengajuan.store');
    Route::get('/jadwal', [SiswaController::class, 'jadwal'])->name('jadwal');
    Route::get('/riwayat', [SiswaController::class, 'riwayat'])->name('riwayat');

    // Kalender Guru BK
    Route::get('/kalender', [SiswaController::class, 'kalender'])->name('kalender');
    Route::get('/api/kalender', [SiswaController::class, 'apiKalender'])->name('api.kalender');
    Route::get('/api/slot-guru', [SiswaController::class, 'apiSlotGuru'])->name('api.slot.guru');
});

