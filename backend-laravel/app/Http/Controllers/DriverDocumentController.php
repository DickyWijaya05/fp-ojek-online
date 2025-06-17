<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DriverDocument;
use App\Models\User;

class DriverDocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'vehicle_name' => 'required|string',
            'vehicle_type' => 'required|string',
            'ktp' => 'required|file|image',
            'selfie_ktp' => 'required|file|image',
            'sim' => 'required|file|image',
            'stnk' => 'required|file|image',
            'pas_photo' => 'required|file|image',
            'vehicle_photo' => 'required|file|image',
        ]);

        $driverId = auth()->id();

        $paths = [];
        foreach (['ktp', 'selfie_ktp', 'sim', 'stnk', 'pas_photo', 'vehicle_photo'] as $doc) {
            $paths[$doc] = $request->file($doc)->store("documents/$driverId", 'public');
        }

        $doc = DriverDocument::create([
            'driver_id' => $driverId,
            'vehicle_name' => $request->vehicle_name,
            'vehicle_type' => $request->vehicle_type,
            'ktp' => $paths['ktp'],
            'selfie_ktp' => $paths['selfie_ktp'],
            'sim' => $paths['sim'],
            'stnk' => $paths['stnk'],
            'pas_photo' => $paths['pas_photo'],
            'vehicle_photo' => $paths['vehicle_photo'],
            'status' => 'pending', // default saat pertama upload
        ]);

        return response()->json(['message' => 'Dokumen berhasil diupload', 'data' => $doc]);
    }

    // Untuk admin melihat semua dokumen driver
    public function index()
    {
        $documents = DriverDocument::with('driver')->get();
        return response()->json($documents);
    }

    // Admin memverifikasi
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $document = DriverDocument::findOrFail($id);
        $document->status = $request->status;
        $document->save();

        return response()->json(['message' => 'Status diperbarui']);
    }
}
