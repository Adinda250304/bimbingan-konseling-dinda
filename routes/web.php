<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruBkController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WaliKelasController;

// ===== PUBLIC ROUTES =====
Route::get('/welcome', [AuthController::class, 'showSplash'])->name('splash');
Route::get('/', [AuthController::class, 'showWelcome'])->name('welcome');
Route::get('/about', [AuthController::class, 'showAbout'])->name('about');
Route::get('/layanan', [AuthController::class, 'showLayanan'])->name('layanan');
Route::get('/alur', [AuthController::class, 'showAlur'])->name('alur');
Route::get('/faq', [AuthController::class, 'showFaq'])->name('faq');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== ADMIN ROUTES =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password');

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
});

// ===== GURU BK ROUTES =====
Route::prefix('guru-bk')->name('guru_bk.')->middleware(['auth', 'role:guru_bk'])->group(function () {
    Route::get('/dashboard', [GuruBkController::class, 'dashboard'])->name('dashboard');

    // Kalender Guru BK
    Route::get('/kalender', [GuruBkController::class, 'kalender'])->name('kalender');
    Route::get('/api/kalender', [GuruBkController::class, 'apiKalender'])->name('api.kalender');
    Route::post('/api/kalender', [GuruBkController::class, 'apiKalenderStore'])->name('api.kalender.store');
    Route::put('/api/kalender/{kalender}', [GuruBkController::class, 'apiKalenderUpdate'])->name('api.kalender.update');
    Route::delete('/api/kalender/{kalender}', [GuruBkController::class, 'apiKalenderDestroy'])->name('api.kalender.destroy');

    // Profile
    Route::get('/profile', [GuruBkController::class, 'profile'])->name('profile');
    Route::put('/profile', [GuruBkController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [GuruBkController::class, 'updatePassword'])->name('profile.password');

    // Jadwal
    Route::get('/jadwal', [GuruBkController::class, 'jadwal'])->name('jadwal');
    Route::post('/jadwal/konseling', [GuruBkController::class, 'storeKonseling'])->name('jadwal.konseling.store');
    Route::post('/jadwal', [GuruBkController::class, 'storeJadwal'])->name('jadwal.store');
    Route::put('/jadwal/{jadwal}', [GuruBkController::class, 'updateJadwal'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwal}', [GuruBkController::class, 'deleteJadwal'])->name('jadwal.delete');

    // Riwayat & Hasil
    Route::get('/riwayat', [GuruBkController::class, 'riwayat'])->name('riwayat');
    Route::get('/konseling/{konseling}', [GuruBkController::class, 'showKonseling'])->name('konseling.show');
    Route::put('/konseling/{konseling}', [GuruBkController::class, 'updateKonseling'])->name('konseling.update');
    Route::put('/konseling/{konseling}/status', [GuruBkController::class, 'updateStatusKonseling'])->name('konseling.status');
    Route::post('/konseling/{konseling}/advance', [GuruBkController::class, 'advanceStatus'])->name('konseling.advance');
    Route::post('/konseling/{konseling}/setujui', [GuruBkController::class, 'setujuiPengajuan'])->name('konseling.setujui');
    Route::post('/konseling/{konseling}/tolak', [GuruBkController::class, 'tolakPengajuan'])->name('konseling.tolak');
    Route::delete('/konseling/{konseling}', [GuruBkController::class, 'deleteKonseling'])->name('konseling.delete');
    Route::get('/konseling/{konseling}/hasil', [GuruBkController::class, 'hasilKonseling'])->name('konseling.hasil');
    Route::post('/konseling/{konseling}/hasil', [GuruBkController::class, 'storeHasil'])->name('konseling.hasil.store');

    // Laporan & Rekap
    Route::get('/laporan', [GuruBkController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/pdf', [GuruBkController::class, 'laporanPdf'])->name('laporan.pdf');

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
    Route::post('/konseling/{konseling}/rate', [SiswaController::class, 'storeFeedback'])->name('konseling.rate');

    // Kalender Guru BK
    Route::get('/kalender', [SiswaController::class, 'kalender'])->name('kalender');
    Route::get('/api/kalender', [SiswaController::class, 'apiKalender'])->name('api.kalender');
    Route::get('/api/slot-guru', [SiswaController::class, 'apiSlotGuru'])->name('api.slot.guru');
});

// ===== WALI KELAS ROUTES =====
Route::prefix('wali')->name('wali.')->middleware(['auth', 'role:wali_kelas'])->group(function () {
    Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('dashboard');
    Route::get('/siswa', [WaliKelasController::class, 'siswa'])->name('siswa');
    Route::get('/rujuk/{siswa_id}', [WaliKelasController::class, 'rujuk'])->name('rujuk');
    Route::post('/rujuk/{siswa_id}', [WaliKelasController::class, 'storeRujukan'])->name('rujuk.store');
    Route::get('/jadwal', [WaliKelasController::class, 'jadwal'])->name('jadwal');
    Route::get('/riwayat', [WaliKelasController::class, 'riwayat'])->name('riwayat');
});

// ===== NOTIFICATION ROUTES =====
Route::middleware(['auth'])->group(function () {
    Route::get('/api/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/api/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::post('/api/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
});
