<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function verify($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->is_verified = true;
        $user->save();

        return redirect()->back()->with('success', 'User berhasil diverifikasi.');
    }

    public function activate($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'Status user berhasil diubah.');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }

    public function drivers()
    {
        return view('admin.drivers');
    }

    public function statusDriver()
    {
        return view('admin.status-driver');
    }

    public function tarif()
    {
        return view('admin.tarif');
    }

    public function laporanTransaksi()
    {
        return view('admin.laporan-transaksi');
    }

    public function laporanAktivitas()
    {
        return view('admin.laporan-aktivitas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
