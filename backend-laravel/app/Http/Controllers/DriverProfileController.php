<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DriverProfileController extends Controller
{
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || $user->level_id != 2) {
                return response()->json(['message' => 'Unauthorized or not a driver'], 403);
            }

            // Ambil atau buat data driver jika belum ada
            $driver = $user->driver ?? $user->driver()->create([
                'user_id' => $user->id,
                'status' => 'aktif',
                'rating' => 0
            ]);

            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'foto_profil' => $driver->foto_profil ? asset('storage/profiles/' . $driver->foto_profil) : null,
                'gender' => $driver->jenis_kelamin === 'L' ? 'Male' : ($driver->jenis_kelamin === 'P' ? 'Female' : null),
                'status' => $driver->status,
                'rating' => $driver->rating,
                'alamat' => $driver->alamat,
            ]);
        } catch (\Throwable $e) {
            Log::error('DriverProfile error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user || $user->level_id != 2) {
                return response()->json(['message' => 'Unauthorized or not a driver'], 403);
            }

            $driver = $user->driver ?? $user->driver()->create([
                'user_id' => $user->id,
                'status' => 'aktif',
                'rating' => 0
            ]);

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:255',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat' => 'nullable|string|max:255'
            ]);

            $user->fill(array_filter($validated, fn($v, $k) => in_array($k, ['name', 'phone']), ARRAY_FILTER_USE_BOTH))->save();
            $driver->fill(array_filter($validated, fn($v, $k) => in_array($k, ['jenis_kelamin', 'alamat']), ARRAY_FILTER_USE_BOTH))->save();

            return response()->json(['message' => 'Profile updated']);
        } catch (\Throwable $e) {
            Log::error('Update driver profile error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update profile'], 500);
        }
    }

    public function uploadPhoto(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user || $user->level_id != 2) {
                return response()->json(['error' => 'Unauthorized or not a driver'], 403);
            }

            $driver = $user->driver ?? $user->driver()->create([
                'user_id' => $user->id,
                'status' => 'aktif',
                'rating' => 0
            ]);

            $request->validate([
                'foto_profil' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $path = $request->file('foto_profil')->store('public/profiles');
            $filename = basename($path);

            $driver->foto_profil = $filename;
            $driver->save();

            return response()->json([
                'foto_profil' => asset('storage/profiles/' . $filename)
            ]);
        } catch (\Throwable $e) {
            Log::error('Upload driver photo error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to upload photo'], 500);
        }
    }
}
