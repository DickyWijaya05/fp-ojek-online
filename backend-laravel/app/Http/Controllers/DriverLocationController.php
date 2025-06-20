<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverLocation;

class DriverLocationController extends Controller
{
    public function store(Request $request)
    {
        // Ambil ID driver dari user yang sedang login (via sanctum)
        $driverId = auth()->id();

        // Validasi input hanya latitude & longitude
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Simpan atau update lokasi driver
        $location = DriverLocation::updateOrCreate(
            ['driver_id' => $driverId],
            [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'status' => 'available',
            ]
        );

        return response()->json([
            'message' => 'Lokasi driver berhasil disimpan.',
            'data' => $location
        ]);
    }
}
