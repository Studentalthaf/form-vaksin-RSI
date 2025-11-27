<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermohonanPasienController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\ScreeningQuestionCategoryController;
use App\Http\Controllers\Admin\ScreeningQuestionController;
use App\Http\Controllers\Admin\ScreeningPasienController;
use App\Http\Controllers\Dokter\DokterDashboardController;

// Redirect root ke form permohonan
Route::get('/', function () {
    return redirect()->route('permohonan.create');
});

// Public Routes - Form Permohonan Vaksinasi (tanpa login)
Route::get('/permohonan', [PermohonanController::class, 'create'])->name('permohonan.create');
Route::post('/permohonan', [PermohonanController::class, 'store'])->name('permohonan.store');
Route::get('/permohonan/screening/{vaccine_request_id}', [PermohonanController::class, 'showScreening'])->name('permohonan.screening');
Route::post('/permohonan/screening/{vaccine_request_id}', [PermohonanController::class, 'storeScreening'])->name('permohonan.screening.store');
Route::get('/permohonan/sukses', [PermohonanController::class, 'success'])->name('permohonan.success');

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Protected routes (requires authentication)
Route::middleware('auth')->group(function () {
    // Dashboard utama - redirect berdasarkan role
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin_rumah_sakit') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('dokter.dashboard');
        }
    })->name('dashboard');
    
    // Admin routes - hanya untuk admin_rumah_sakit
    Route::middleware('role:admin_rumah_sakit')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // User Management Routes
        Route::resource('admin/users', UserController::class, [
            'as' => 'admin'
        ]);
        
        // Permohonan Pasien Routes
        Route::get('admin/permohonan', [PermohonanPasienController::class, 'index'])->name('admin.permohonan.index');
        Route::get('admin/permohonan-terverifikasi', [PermohonanPasienController::class, 'terverifikasi'])->name('admin.permohonan.terverifikasi');
        Route::get('admin/permohonan-terverifikasi/{permohonan}', [PermohonanPasienController::class, 'showTerverifikasi'])->name('admin.permohonan.terverifikasi.show');
        Route::get('admin/permohonan-terverifikasi/{permohonan}/cetak-pdf', [PermohonanPasienController::class, 'cetakPdfTerverifikasi'])->name('admin.permohonan.terverifikasi.cetak-pdf');
        Route::get('admin/permohonan/{permohonan}', [PermohonanPasienController::class, 'show'])->name('admin.permohonan.show');
        Route::delete('admin/permohonan/{permohonan}', [PermohonanPasienController::class, 'destroy'])->name('admin.permohonan.destroy');
        
        // Nilai Screening Routes (Admin memberi nilai screening)
        Route::get('admin/screening/{permohonan}/nilai', [ScreeningPasienController::class, 'show'])->name('admin.screening.show');
        Route::post('admin/screening/{permohonan}/nilai', [ScreeningPasienController::class, 'storeNilai'])->name('admin.screening.nilai.store');
        Route::get('admin/screening/{permohonan}/nilai/edit', [ScreeningPasienController::class, 'editNilai'])->name('admin.screening.nilai.edit');
        Route::put('admin/screening/{permohonan}/nilai', [ScreeningPasienController::class, 'updateNilai'])->name('admin.screening.nilai.update');
        Route::post('admin/screening/{permohonan}/assign-dokter', [ScreeningPasienController::class, 'assignDokter'])->name('admin.screening.assign-dokter');
        Route::put('admin/screening/{permohonan}/pasien', [ScreeningPasienController::class, 'updatePasien'])->name('admin.screening.pasien.update');
        Route::put('admin/screening/{permohonan}/vaksin', [ScreeningPasienController::class, 'updateVaksin'])->name('admin.screening.vaksin.update');
        Route::put('admin/screening/{permohonan}/jawaban', [ScreeningPasienController::class, 'updateJawaban'])->name('admin.screening.jawaban.update');
        
        // Data Pasien Routes
        Route::get('admin/pasien', [PasienController::class, 'index'])->name('admin.pasien.index');
        Route::get('admin/pasien/{pasien}', [PasienController::class, 'show'])->name('admin.pasien.show');
        Route::patch('admin/pasien/{pasien}/update-rm', [PasienController::class, 'updateNomorRM'])->name('admin.pasien.update-rm');
        
        // Screening Question Category Routes
        Route::resource('admin/screening/categories', ScreeningQuestionCategoryController::class, [
            'as' => 'admin.screening'
        ]);
        
        // Screening Question Routes
        Route::resource('admin/screening/questions', ScreeningQuestionController::class, [
            'as' => 'admin.screening'
        ]);
        
        // Vaksin Routes
        Route::resource('admin/vaksin', \App\Http\Controllers\Admin\VaksinController::class, [
            'as' => 'admin'
        ]);
    });
    
    // Dokter routes - hanya untuk dokter
    Route::middleware('role:dokter')->group(function () {
        // Dashboard - AKTIF
        Route::get('/dokter/dashboard', [DokterDashboardController::class, 'index'])->name('dokter.dashboard');
        
        // Daftar Pasien
        Route::get('/dokter/pasien', [DokterDashboardController::class, 'daftarPasien'])->name('dokter.pasien.index');
        Route::get('/dokter/pasien/{id}/detail', [DokterDashboardController::class, 'detailPasien'])->name('dokter.pasien.show');
        Route::post('/dokter/pasien/{id}/konfirmasi', [DokterDashboardController::class, 'konfirmasiPasien'])->name('dokter.pasien.konfirmasi');
        Route::put('/dokter/pasien/{id}/jawaban', [DokterDashboardController::class, 'updateJawaban'])->name('dokter.pasien.jawaban.update');
        Route::put('/dokter/pasien/{id}/vaksin', [DokterDashboardController::class, 'updateVaksin'])->name('dokter.pasien.vaksin.update');
    });
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
