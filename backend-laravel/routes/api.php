<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\DriverProfileController;
use App\Http\Controllers\DriverDocumentController;
use App\Http\Controllers\Api\CustomerLocationController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\DriverLocationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\Api\OrderPollingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RatingController;


// Rute publik
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/register-driver', [ApiAuthController::class, 'registerDriver']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/store-user', [ApiAuthController::class, 'storeUser']);
Route::post('/get-user', [ApiAuthController::class, 'getUser']);

//route gps
Route::get('/search-location', function () {
    $query = request('q');
    $country = request('country') ?? 'ID';
    $response = Http::withHeaders([
        'User-Agent' => 'GoCabsApp/1.0 (dickiajiwijaya@.com)'
    ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'countrycodes' => $country
            ]);

    return $response->json();
});

Route::post('/route', [RouteController::class, 'getRoute']);

// Route umum (dengan autentikasi)
Route::middleware('auth:sanctum')->get('/user', fn(Request $request) => $request->user());
// Cek status order berdasarkan ID (untuk customer dan driver)
Route::middleware('auth:sanctum')->get('/order/{id}', [OrderController::class, 'show']);
Route::middleware('auth:sanctum')->get('/order/{id}/driver-qris', [OrderController::class, 'getDriverQris']);
Route::middleware('auth:sanctum')->post('/customer-location', [CustomerLocationController::class, 'store']);
Route::middleware('auth:sanctum')->post('/nearest-driver', [DriverLocationController::class, 'findNearestDriver']);
Route::middleware(['auth:sanctum'])->get('/driver/{id}/location', [DriverLocationController::class, 'getLocation']);
Route::middleware('auth:sanctum')->get('/driver/route-to-destination/{id}', [OrderController::class, 'routeToDestination']);
Route::middleware('auth:sanctum')->post('/driver/order-status/{id}', [OrderController::class, 'updateStatus']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancelOrder']);
});





// ===============================
// === Group Admin Only =========
// ===============================
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/{id}', [AdminUserController::class, 'show']);
    Route::put('/users/{id}/verify', [AdminUserController::class, 'verify']);
    Route::put('/users/{id}/activate', [AdminUserController::class, 'activate']);
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);

    Route::get('/documents', [DriverDocumentController::class, 'index']);
    Route::put('/documents/{id}/verify', [DriverDocumentController::class, 'verify']);

    Route::get('/tarif', [TarifController::class, 'edit'])->name('admin.tarif.edit');
    Route::post('/tarif', [TarifController::class, 'updateWeb'])->name('admin.tarif.update');
});

// ===============================
// === Group Customer Only =======
// ===============================
Route::middleware(['auth:sanctum', 'is_customer'])->group(function () {
    Route::get('/customer/profile', fn(Request $request) => response()->json(['user' => $request->user()]));
    Route::post('/order', [OrderController::class, 'store']);
    Route::post('/customer/order-status/{id}', [OrderController::class, 'customerUpdateStatus']);
    Route::get('/customer/transactions', [OrderController::class, 'customerTransactionHistory']);
    Route::put('/order/{id}/set-payment-method', [OrderController::class, 'setPaymentMethod']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);
    Route::post('/profile/upload-photo', [UserController::class, 'uploadPhoto']);
});


// ===============================
// === Group Driver Only ========= 
// ===============================
Route::middleware(['auth:sanctum', 'is_driver'])->group(function () {

    Route::post('/driver-location', [DriverLocationController::class, 'store']);
    Route::post('/driver-status', [DriverLocationController::class, 'updateStatus']);

    Route::get('/driver/incoming-orders', [OrderPollingController::class, 'getIncomingOrders']);
    Route::post('/driver/accept-order', [OrderPollingController::class, 'accept']);
    Route::post('/driver/reject-order', [OrderPollingController::class, 'reject']);
    Route::post('/driver/accept-order/{id}', [OrderController::class, 'acceptOrder']);
    Route::get('/driver/incoming-order', [OrderController::class, 'incomingOrder']);
    Route::get('/driver/transactions', [TransactionController::class, 'driverTransactions']);

    // Upload dokumen
    Route::post('/driver/documents', [DriverDocumentController::class, 'upload']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/driver/profile', [DriverProfileController::class, 'profile']);
    Route::put('/driver/profile', [DriverProfileController::class, 'updateProfile']);
    Route::post('/driver/profile/upload-qris', [DriverProfileController::class, 'uploadQris']);
    Route::post('/rate-driver', [RatingController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions', [TransactionController::class, 'index']); // opsional
    Route::get('/payment-details/order/{orderId}', [TransactionController::class, 'getPaymentDetails']);
    Route::get('/driver-profile/order/{orderId}', [TransactionController::class, 'getDriverProfileByOrder']);
});
