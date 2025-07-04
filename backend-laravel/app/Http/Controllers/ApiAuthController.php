<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class ApiAuthController extends Controller
{
    // ✅ REGISTRASI MANUAL CUSTOMER
    public function register(Request $request)
    {
        \Log::info('Register Payload:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'level_id' => 'required|integer',
            'password' => 'required|string|min:8|confirmed', // ✅ gunakan confirmed untuk validasi dengan password_confirmation
        ]);
        $uid = $request->uid ?? Str::uuid()->toString();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // ✅ gunakan password dari user
            'phone' => $request->phone,
            'level_id' => $request->level_id,
            'uid' => $uid, // pasti ada// jika dikirim, simpan
            'photo_url' => $request->photo_url ?? null,
            'status' => 'aktif',
        ]);

        $token = $user->createToken('customer-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // ✅ REGISTRASI DRIVER DARI APLIKASI IONIC
public function registerDriver(Request $request)
{
    \Log::info('Register Driver Payload:', $request->all());

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'nullable|string|max:20',
        'level_id' => 'required|integer',
        'password' => 'required|string|min:8',
    ]);

    $uid = $request->uid ?? Str::uuid()->toString();

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone' => $request->phone,
        'level_id' => $request->level_id,
        'uid' => $uid,
        'photo_url' => $request->photo_url ?? null,
        'status' => 'pending',
    ]);

    $token = $user->createToken('driver-register')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
}


    // ✅ LOGIN MANUAL CUSTOMER
 public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    // ⛔ Cegah login manual jika password kosong (akun dari Google)
    if (empty($user->password)) {
        return response()->json([
            'message' => 'Akun ini terdaftar menggunakan Google. Silakan login dengan Google.'
        ], 403);
    }

    if (!$user) {
        return response()->json(['message' => 'Email belum terdaftar.'], 404);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    // Akun belum lengkap
    if ($user->level_id == 1) {
        return response()->json(['message' => 'Akun belum lengkap. Silakan daftar terlebih dahulu.'], 403);
    }

    // Level tidak valid
    if (!in_array($user->level_id, [2, 3])) {
        return response()->json(['message' => 'Level pengguna tidak valid.'], 403);
    }

    // Jika customer dan status tidak aktif
if ($user->level_id == 3 && $user->status !== 'aktif') {
    return response()->json([
        'message' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.'
    ], 403);
}

// Jika driver dan status tidak aktif
    if ($user->level_id == 2 && $user->status !== 'aktif') {
        return response()->json([
            'message' => 'Akun Anda belum aktif. Silakan tunggu verifikasi dari admin.'
        ], 403);
    }

    // Jika customer (level_id = 3), buat entri ke tabel customers jika belum ada
    if ($user->level_id == 3) {
        $isCustomerExist = Customer::where('user_id', $user->id)->exists();
        if (!$isCustomerExist) {
            Customer::create([
                'user_id' => $user->id,
                'foto_profil' => $user->photo_url,
                'alamat' => null,
                'jenis_kelamin' => null,
                'rating' => 0.00
            ]);
        }
    }

    $token = $user->createToken('auth-token')->plainTextToken;

    // ✅ Tambahkan ke tabel drivers jika level_id == 2 (driver) dan belum ada
    if ($user->level_id == 2) {
        $exists = Driver::where('user_id', $user->id)->exists();
        if (!$exists) {
            Driver::create([
                'user_id' => $user->id,
                'foto_profil' => $user->photo_url,
                'alamat' => null,
                'status' => 'offline',
                'rating' => 0.0,
            ]);
        }

        // ✅ Simpan log aktivitas login driver
        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'level_id' => $user->level_id,
            'activity' => 'Login',
            'ip_address' => request()->ip(),
            'device' => request()->userAgent(),
        ]);
    }

    return response()->json([
        'user' => $user,
        'token' => $token,
    ]);
}


    // ✅ DARI FIREBASE (Google/OTP)
    public function storeUser(Request $request)
    {
        \Log::info('Request Data:', $request->all());

        $request->validate([
            'uid' => 'required|string',
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'photo_url' => 'nullable|url',
        ]);

        if ($request->email) {
            $emailExists = User::where('email', $request->email)
                ->where('uid', '!=', $request->uid)
                ->exists();

            if ($emailExists) {
                return response()->json([
                    'message' => 'Email sudah digunakan oleh user lain.'
                ], 422);
            }
        }

        $user = User::where('uid', $request->uid)->first();

if ($user) {
    // Update data kecuali level_id
    $user->update([
        'name' => $request->name ?? $user->name,
        'email' => $request->email ?? $user->email,
        'phone' => $request->phone ?? $user->phone,
        'photo_url' => $request->photo_url ?? $user->photo_url,
    ]);
} else {
    // Buat user baru
    $user = User::create([
        'uid' => $request->uid,
        'name' => $request->name ?? 'User',
        'email' => $request->email,
        'phone' => $request->phone,
        'photo_url' => $request->photo_url,
        'level_id' => $request->level_id ?? 3, // <- Gunakan level_id dari frontend
        'password' => null,
        'status' => 'aktif',
    ]);
}

            // ✅ Tambahkan juga ke tabel customers jika belum ada
    $isCustomerExist = Customer::where('user_id', $user->id)->exists();

    if (!$isCustomerExist) {
        Customer::create([
            'user_id' => $user->id,
            'foto_profil' => $user->photo_url,
            'alamat' => null,
            'jenis_kelamin' => null,
            'rating' => 0.0
        ]);
    }


        $token = $user->createToken('firebase-login')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
    

    // ✅ AMBIL DATA USER BERDASARKAN EMAIL
// ✅ Ambil user berdasarkan email
public function getUser(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'user' => $user
    ]);
}


}
