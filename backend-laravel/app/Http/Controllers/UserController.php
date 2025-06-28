<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Menampilkan profil user yang sedang login (gabungan dengan customer)
     */
    public function profile(Request $request)
    {
        $user = $request->user()->load('customer');

        return response()->json([
            'name'        => $user->name,
            'email'       => $user->email,
            'phone'       => $user->phone,
           'foto_profil' => $user->customer->foto_profil
    ? (Str::startsWith($user->customer->foto_profil, 'http') 
        ? $user->customer->foto_profil 
        : asset('storage/' . $user->customer->foto_profil))
    : null,

            'gender'      => $user->customer->jenis_kelamin == 'L' ? 'Male' : 'Female',
            'address'     => $user->customer->alamat,
        ]);
    }

    /**
     * Mengupdate data profil user dan customer
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;

        $validated = $request->validate([
            'name'           => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:255',
            'alamat'         => 'nullable|string',
            'jenis_kelamin'  => 'nullable|in:L,P',
        ]);

        // Update ke tabel users
        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }

        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }

        $user->save();

        // Update ke tabel customers
        if ($customer) {
            if (isset($validated['alamat'])) {
                $customer->alamat = $validated['alamat'];
            }

            if (isset($validated['jenis_kelamin'])) {
                $customer->jenis_kelamin = $validated['jenis_kelamin'];
            }

            $customer->save();
        }

        return response()->json(['message' => 'Profile updated successfully']);
    }

    public function uploadPhoto(Request $request)
{
    $request->validate([
        'foto_profil' => 'required|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
    ]);

    $user = $request->user();
    $customer = $user->customer;

    // Hapus foto lama jika ada
    if ($customer && $customer->foto_profil) {
        Storage::disk('public')->delete($customer->foto_profil);
    }

    // Simpan foto baru
    $file = $request->file('foto_profil');
    $path = $file->store('foto_profil', 'public'); // disimpan di storage/app/public/foto_profil

    // Update ke kolom customer
    if ($customer) {
        $customer->foto_profil = $path;
        $customer->save();
    }

    return response()->json([
        'message' => 'Foto profil berhasil diunggah',
        'foto_profil' => asset('storage/' . $path)
    ]);
}


    /**
     * (Optional) Untuk admin atau debug, lihat semua user
     */
    public function index()
    {
        return response()->json(User::all());
    }
}
