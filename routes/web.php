<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\Admin\PermohonanPasienController;
use App\Http\Controllers\Admin\ScreeningQuestionCategoryController;
use App\Http\Controllers\Admin\ScreeningQuestionController;
use App\Http\Controllers\Admin\ScreeningPasienController;

// Redirect root ke form permohonan
Route::get('/', function () {
    return redirect()->route('permohonan.create');
});

// Public Routes - Form Permohonan Vaksinasi (tanpa login)
Route::get('/permohonan', [PermohonanController::class, 'create'])->name('permohonan.create');
Route::post('/permohonan', [PermohonanController::class, 'store'])->name('permohonan.store');
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
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        
        // User Management Routes
        Route::resource('admin/users', UserController::class, [
            'as' => 'admin'
        ]);
        
        // Permohonan Pasien Routes
        Route::get('admin/permohonan', [PermohonanPasienController::class, 'index'])->name('admin.permohonan.index');
        Route::get('admin/permohonan/{permohonan}', [PermohonanPasienController::class, 'show'])->name('admin.permohonan.show');
        Route::patch('admin/permohonan/{permohonan}/approve', [PermohonanPasienController::class, 'approve'])->name('admin.permohonan.approve');
        Route::patch('admin/permohonan/{permohonan}/reject', [PermohonanPasienController::class, 'reject'])->name('admin.permohonan.reject');
        Route::delete('admin/permohonan/{permohonan}', [PermohonanPasienController::class, 'destroy'])->name('admin.permohonan.destroy');
        
        // Screening Pasien Routes
        Route::get('admin/screening/pasien/{permohonan}/create', [ScreeningPasienController::class, 'create'])->name('admin.screening.pasien.create');
        Route::post('admin/screening/pasien/{permohonan}', [ScreeningPasienController::class, 'store'])->name('admin.screening.pasien.store');
        Route::get('admin/screening/pasien/{permohonan}/show', [ScreeningPasienController::class, 'show'])->name('admin.screening.pasien.show');
        
        // Screening Question Category Routes
        Route::resource('admin/screening/categories', ScreeningQuestionCategoryController::class, [
            'as' => 'admin.screening'
        ]);
        
        // Screening Question Routes
        Route::resource('admin/screening/questions', ScreeningQuestionController::class, [
            'as' => 'admin.screening'
        ]);
    });
    
    // Dokter routes - hanya untuk dokter
    Route::middleware('role:dokter')->group(function () {
        Route::get('/dokter/dashboard', function () {
            return view('dokter.dashboard');
        })->name('dokter.dashboard');
        
        // Tambahkan route dokter lainnya di sini
    });
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
