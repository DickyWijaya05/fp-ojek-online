<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DriverController extends Controller
{
    // Tampilkan semua driver
    public function index()
    {
        $drivers = User::where('level_id', 2)->paginate(10);
        return view('admin.drivers.index', compact('drivers'));
    }

    // Tampilkan form tambah driver
    public function create()
    {
        return view('admin.drivers.create');
    }

    // Simpan driver baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'level_id' => 2, // Set level driver
            'status' => 'active',
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver berhasil ditambahkan.');
    }

    // Tampilkan form edit driver
    public function edit($id)
    {
        $driver = User::where('level_id', 2)->findOrFail($id);
        return view('admin.drivers.edit', compact('driver'));
    }

    // Update data driver
    public function update(Request $request, $id)
    {
        $driver = User::where('level_id', 2)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $driver->id,
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $driver->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Data driver berhasil diperbarui.');
    }

    // Hapus driver
    public function destroy($id)
    {
        $driver = User::where('level_id', 2)->findOrFail($id);
        $driver->delete();

        return redirect()->route('admin.drivers.index')->with('success', 'Driver berhasil dihapus.');
    }
}
