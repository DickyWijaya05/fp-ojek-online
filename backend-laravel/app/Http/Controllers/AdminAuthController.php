<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); // pastikan view admin/login.blade.php ada
    }


public function login(Request $request)
{
    // Validasi input
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Coba login dengan email dan password
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        // Cek level_id user yang login
        if (Auth::user()->level_id == 1) {
            // Berhasil login sebagai admin, redirect ke dashboard
            return redirect()->route('admin.dashboard');
        } else {
            // Logout karena bukan admin
            Auth::logout();
            return redirect()->route('admin.login')->with('error', 'Anda bukan admin.');
        }
    }

    // Jika gagal login
    return redirect()->route('admin.login')->with('error', 'Email atau password salah.');
}

}
