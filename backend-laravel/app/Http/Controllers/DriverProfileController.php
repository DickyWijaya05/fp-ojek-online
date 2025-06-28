<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

            $driver = $user->driver;

            if (!$driver) {
                return response()->json(['message' => 'Driver profile not found'], 404);
            }

            return response()->json([
                'name'         => $user->name,
                'email'        => $user->email,
                'phone'        => $user->phone,
                'foto_profil'  => $driver->foto_profil ? asset('storage/' . $driver->foto_profil) : null,
                'gender'       => $driver->jenis_kelamin === 'L' ? 'Male' : ($driver->jenis_kelamin === 'P' ? 'Female' : null),
                'status'       => $driver->status,
                'rating'       => $driver->rating,
                'alamat'       => $driver->alamat,
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

            $driver = $user->driver;

            $validated = $request->validate([
                'name'          => 'nullable|string|max:255',
                'phone'         => 'nullable|string|max:255',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat'        => 'nullable|string|max:255'
            ]);

            $user->fill(array_filter($validated, fn($v, $k) => in_array($k, ['name', 'phone']), ARRAY_FILTER_USE_BOTH))->save();

            if ($driver) {
                $driver->fill(array_filter($validated, fn($v, $k) => in_array($k, ['jenis_kelamin', 'alamat']), ARRAY_FILTER_USE_BOTH))->save();
            }

            return response()->json(['message' => 'Profile updated']);
        } catch (\Throwable $e) {
            Log::error('Update driver profile error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update profile'], 500);
        }
    }

    public function uploadPhoto(Request $request)
    {
        try {
            $request->validate([
                'foto_profil' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $user = $request->user();

            if (!$user || $user->level_id != 2) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $driver = $user->driver;

            if (!$driver) {
                return response()->json(['message' => 'Driver not found'], 404);
            }

            if ($driver->foto_profil) {
                Storage::disk('public')->delete($driver->foto_profil);
            }

            $file = $request->file('foto_profil');
            $path = $file->store('driver_foto', 'public');

            $driver->foto_profil = $path;
            $driver->save();

            return response()->json([
                'message'     => 'Foto profil berhasil diunggah',
                'foto_profil' => asset('storage/' . $path)
            ]);
        } catch (\Throwable $e) {
            Log::error('Upload photo error: ' . $e->getMessage());
            return response()->json(['message' => 'Upload failed'], 500);
        }
    }
}
