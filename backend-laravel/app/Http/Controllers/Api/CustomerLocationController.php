<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerLocation;
use Illuminate\Support\Facades\Auth;

class CustomerLocationController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user(); // Ambil user yang sedang login

        $validated = $request->validate([
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'start_address' => 'nullable|string',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'dest_address' => 'nullable|string',
            'distance_km' => 'required|numeric',
            'duration_min' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        $location = CustomerLocation::create([
            'customer_id'    => $user->id,
            'start_lat'      => $validated['start_lat'],
            'start_lng'      => $validated['start_lng'],
            'start_address'  => $validated['start_address'] ?? null,
            'dest_lat'       => $validated['dest_lat'],
            'dest_lng'       => $validated['dest_lng'],
            'dest_address'   => $validated['dest_address'] ?? null,
            'distance_km'    => $validated['distance_km'],
            'duration_min'   => $validated['duration_min'],
            'total_price'    => $validated['total_price'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi pelanggan dan tarif berhasil disimpan',
            'data' => $location,
        ]);
    }
}
