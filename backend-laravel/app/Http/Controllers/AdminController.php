<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class AdminController extends Controller
{
    // Tampilkan halaman profil
    public function profile()
    {
        $admin = Auth::user(); // atau Auth::guard('admin')->user() jika pakai guard admin
        return view('admin.profile', compact('admin'));
    }

    // Tampilkan dashboard
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // Halaman edit profile (opsional)
    public function editProfile()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    // Proses update profil
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $admin->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($admin->photo_url && Storage::disk('public')->exists('avatars/' . $admin->photo_url)) {
                Storage::disk('public')->delete('avatars/' . $admin->photo_url);
            }

            $file = $request->file('avatar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $filename, 'public');
            $admin->photo_url = $filename; // simpan ke kolom photo_url
        }

        $admin->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // Proses ganti password
    public function changePassword(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        return back()->with('success', 'Password berhasil diubah.');
    }

    // Manajemen pengguna
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
        return view('admin.tarif.edit');
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
