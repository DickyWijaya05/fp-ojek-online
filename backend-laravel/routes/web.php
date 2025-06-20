<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Admin\TarifController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Halaman Login Admin ===
Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.edit-profile');
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
Route::post('/admin/profile/change-password', [AdminController::class, 'changePassword'])->name('admin.profile.change-password');


// === Group Route Admin (yang sudah login dan admin) ===
Route::middleware(['auth', 'admin']) // middleware untuk keamanan akses
    ->prefix('admin')                // prefix URL /admin
    ->as('admin.')                  // prefix nama route "admin."
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        // Admin Profil
        Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');

        // Manajemen User
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{id}/verify', [AdminController::class, 'verify'])->name('users.verify');
        Route::put('/users/{id}/activate', [AdminController::class, 'activate'])->name('users.activate');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');

        // Manajemen Status Driver
        Route::get('/status-driver', [AdminController::class, 'statusDriver'])->name('status-driver');

        // Tarif dan Laporan
        Route::get('/tarif', [TarifController::class, 'edit'])->name('tarif');
        Route::post('/tarif', [TarifController::class, 'updateWeb'])->name('tarif.update');
        Route::get('/laporan-transaksi', [AdminController::class, 'laporanTransaksi'])->name('laporan-transaksi');
        Route::get('/laporan-aktivitas', [AdminController::class, 'laporanAktivitas'])->name('laporan-aktivitas');

        // Logout
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        // Manajemen Driver (CRUD)
        Route::resource('drivers', DriverController::class);
    });
