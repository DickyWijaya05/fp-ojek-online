<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TarifConfig;

class RouteController extends Controller
{
    public function getRoute(Request $request)
    {
        $coordinates = $request->input('coordinates');

        if (!$coordinates || count($coordinates) !== 2) {
            return response()->json(['error' => 'Koordinat tidak valid'], 400);
        }

        $apiKey = env('ORS_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => $apiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.openrouteservice.org/v2/directions/driving-car/geojson', [
            'coordinates' => $coordinates
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal mengambil rute dari OpenRouteService'], 500);
        }

        $data = $response->json();

        if (!isset($data['features'][0]['properties']['segments'][0])) {
            return response()->json(['error' => 'Data rute tidak ditemukan'], 500);
        }

        $segment = $data['features'][0]['properties']['segments'][0];
        $distance = round($segment['distance'] / 1000, 2); // KM
        $duration = round($segment['duration'] / 60, 1);   // Menit

        // Ambil tarif per KM dari database
        $tarifConfig = TarifConfig::first();
        $price_per_km = $tarifConfig ? $tarifConfig->price_per_km : 5000;

        $total_price = ceil($distance * $price_per_km);

        return response()->json([
            'route_geojson' => $data['features'][0]['geometry'],
            'distance_km' => $distance,
            'duration_min' => $duration,
            'total_price' => $total_price
        ]);
    }
}
