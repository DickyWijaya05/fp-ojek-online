<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class DriverController extends Controller
{
    // Tampilkan semua driver
    public function index()
    {
        $drivers = Driver::paginate(10); // Atau ganti 10 sesuai jumlah yang kamu mau

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
            'email' => 'required|email|unique:drivers,email',
            'phone' => 'required|string|max:20',
            'vehicle' => 'required|string|max:100',
        ]);

        Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle' => $request->vehicle,
            'status' => $request->status ?? 'aktif',
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil ditambahkan.');
    }

    // Tampilkan form edit driver
    public function edit($id)
    {
        $driver = Driver::findOrFail($id);
        return view('admin.drivers.edit', compact('driver'));
    }

    // Update data driver
    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:drivers,email,' . $driver->id,
            'phone' => 'required|string|max:20',
            'vehicle' => 'required|string|max:100',
        ]);

        $driver->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'vehicle' => $request->vehicle,
            'status' => $request->status ?? 'aktif',
        ]);

        return redirect()->route('drivers.index')->with('success', 'Data driver berhasil diperbarui.');
    }

    // Hapus driver
    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil dihapus.');
    }
}
