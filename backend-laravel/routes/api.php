<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\DriverDocumentController;
use Illuminate\Support\Facades\Http;

Route::get('/search-location', function () {
    $query = request('q');
    $country = request('country') ?? 'ID'; // default Indonesia

    $response = Http::withHeaders([
        'User-Agent' => 'GoCabsApp/1.0 (dickiajiwijaya@.com)' // ganti dengan identitas aplikasimu
    ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'countrycodes' => $country
            ]);

    return $response->json();
});


Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/register-driver', [ApiAuthController::class, 'registerDriver']);
Route::post('/login', [ApiAuthController::class, 'login']);

// Route baru untuk menyimpan data user dari Firebase (Google/Phone OTP)
Route::post('/store-user', [ApiAuthController::class, 'storeUser']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin only
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/{id}', [AdminUserController::class, 'show']);
    Route::put('/users/{id}/verify', [AdminUserController::class, 'verify']);
    Route::put('/users/{id}/activate', [AdminUserController::class, 'activate']);
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
});

// Customer only
Route::middleware(['auth:sanctum', 'is_customer'])->group(function () {
    Route::get('/customer/profile', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });

    // Tambahkan route customer lainnya di sini
});

// Driver only
Route::middleware(['auth:sanctum', 'is_driver'])->group(function () {
    Route::get('/driver/profile', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });


    // Upload dokumen (akses driver, pakai token)
    Route::middleware('auth:sanctum')->post('/driver/documents', [DriverDocumentController::class, 'upload']);

    // Melihat semua dokumen (akses admin)
    Route::get('/admin/documents', [DriverDocumentController::class, 'index']);

    // Verifikasi status (akses admin)
    Route::put('/admin/documents/{id}/verify', [DriverDocumentController::class, 'verify']);

    // Tambahkan route driver lainnya di sini
});
