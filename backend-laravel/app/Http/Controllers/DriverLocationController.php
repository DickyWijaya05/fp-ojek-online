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


    public function updateStatus(Request $request)
    {
        $driverId = auth()->id();

        $validated = $request->validate([
            'status' => 'required|string|in:available,offline',
        ]);

        $location = DriverLocation::firstOrNew(['driver_id' => $driverId]);

        $location->status = $validated['status'];
        // isi default lokasi kalau belum ada
        $location->latitude = $location->latitude ?? 0;
        $location->longitude = $location->longitude ?? 0;
        $location->save();

        return response()->json([
            'message' => '✅ Status driver diperbarui',
            'data' => $location
        ]);
    }





    public function findNearestDriver(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $validated['latitude'];
        $longitude = $validated['longitude'];
        $radius = 5; // dalam KM, bisa kamu ubah

        // Query dengan Haversine formula
        $nearestDriver = DriverLocation::selectRaw("
    driver_id,
    latitude,
    longitude,
    status,
    (
        6371 * acos(
            cos(radians(?)) * cos(radians(latitude)) *
            cos(radians(longitude) - radians(?)) +
            sin(radians(?)) * sin(radians(latitude))
        )
    ) AS distance
        ", [$latitude, $longitude, $latitude])
            ->with('user:id,name')
            ->where('status', 'available')
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc')
            ->get();
        if ($nearestDriver->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada driver dalam radius ' . $radius . ' KM.',
                'data' => []
            ], 404);
        }


        return response()->json([
            'message' => 'Driver terdekat ditemukan.',
            'data' => $nearestDriver
        ]);
    }
public function getLocation($driverId)
{
    $location = DriverLocation::where('driver_id', $driverId)->first();

    if (!$location) {
        return response()->json(['message' => 'Lokasi belum tersedia'], 404);
    }

    return response()->json([
        'latitude' => $location->latitude,
        'longitude' => $location->longitude,
    ]);
}

}
