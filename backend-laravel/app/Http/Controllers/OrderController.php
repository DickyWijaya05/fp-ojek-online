<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;



class OrderController extends Controller
{
    // Membuat order baru (oleh customer)
   public function store(Request $request)
{
    \Log::info('Payload Order:', $request->all());

    // Validasi customer
    if (!auth()->check() || auth()->user()->level_id != 3) {
        return response()->json(['message' => 'Hanya customer yang bisa membuat order'], 403);
    }

    $request->validate([
        'start_lat' => 'required|numeric',
        'start_lng' => 'required|numeric',
        'dest_lat' => 'required|numeric',
        'dest_lng' => 'required|numeric',
        'start_address' => 'nullable|string',
        'dest_address' => 'nullable|string',
    ]);

    $customerLat = $request->start_lat;
    $customerLng = $request->start_lng;
    $radius = 5; // km

    // Cari driver terdekat yang sedang available
    $nearestDriver = \DB::table('driver_locations')
        ->selectRaw("
            driver_id,
            (
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance
        ", [$customerLat, $customerLng, $customerLat])
        ->where('status', 'available')
        ->having('distance', '<=', $radius)
        ->orderBy('distance')
        ->first();

    if (!$nearestDriver) {
        return response()->json([
            'message' => 'Tidak ada driver tersedia dalam radius ' . $radius . ' KM.'
        ], 404);
    }

    $response = Http::withHeaders([
    'Authorization' => env('ORS_API_KEY'),
    'Content-Type' => 'application/json',
])->post('https://api.openrouteservice.org/v2/directions/driving-car/geojson', [
    'coordinates' => [
        [(float)$request->start_lng, (float)$request->start_lat],
        [(float)$request->dest_lng, (float)$request->dest_lat]
    ]
]);

if ($response->failed()) {
    return response()->json(['message' => 'Gagal menghitung jarak dan durasi'], 500);
}

$summary = $response->json()['features'][0]['properties']['summary'];

$distanceKm = $summary['distance'] / 1000; // meter ke km
$durationMin = $summary['duration'] / 60; // detik ke menit

// Ambil tarif dari database
$tarif = \App\Models\TarifConfig::first();
$pricePerKm = $tarif ? $tarif->price_per_km : 2500; // default 2500 kalau kosong

$totalPrice = round($distanceKm * $pricePerKm);


    $order = Order::create([
        'user_id'       => auth()->id(),
        'driver_id'     => $nearestDriver->driver_id,
        'start_lat'     => $request->start_lat,
        'start_lng'     => $request->start_lng,
        'dest_lat'      => $request->dest_lat,
        'dest_lng'      => $request->dest_lng,
        'start_address' => $request->start_address,
        'dest_address'  => $request->dest_address,
        'status'        => 'pending',
        'distance_km'   => $distanceKm,
        'duration_min'  => $durationMin,
        'total_price'   => $totalPrice,
    ]);

    return response()->json([
        'message' => '✅ Order berhasil dibuat dan dikirim ke driver terdekat.',
        'data' => $order,
    ]);
}


    // Order yang masuk untuk driver tertentu
   public function incomingOrder(Request $request)
{
    \Log::info('Driver Auth:', ['user' => auth()->user()]);

    $user = auth()->user();

    if (!$user || $user->level_id != 2) {
        return response()->json(['message' => 'Hanya driver yang bisa mengakses'], 403);
    }

    $driverId = $user->id;

    // Ambil order 'pending' yang ditugaskan untuk driver ini
    $order = Order::with('user')
        ->where('driver_id', $driverId)
        ->where('status', 'pending')
        ->latest()
        ->first();

    if ($order) {
    $orderArr = $order->toArray();
    $orderArr['driver_lat'] = $order->driver_lat; // kolom baru di DB
    $orderArr['driver_lng'] = $order->driver_lng;
    $orderArr['start_lat'] = $order->start_lat;
    $orderArr['start_lng'] = $order->start_lng;
    $orderArr['distance_km'] = $order->distance_km;
    $orderArr['duration_min'] = $order->duration_min;
    $orderArr['total_price'] = $order->total_price;


      // ✅ Kirim juga route_geojson agar garis rutenya sesuai jalan
        $orderArr['route_geojson'] = $order->route_geojson 
            ? json_decode($order->route_geojson) 
            : null;

    return response()->json(['data' => [$orderArr]]);
}

}



    // Driver menerima order
     public function acceptOrder(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user || $user->level_id != 2) {
            return response()->json(['message' => 'Hanya driver yang bisa menerima order'], 403);
        }

        $order = Order::where('id', $id)
            ->where('driver_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan atau sudah diproses'], 404);
        }

        // Simpan status + posisi driver
        $order->status = 'on_the_way';
        $order->driver_lat = $request->driver_lat;
        $order->driver_lng = $request->driver_lng;
        $order->save();

        // Hitung rute dengan ORS
        $response = Http::withHeaders([
            'Authorization' => env('ORS_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.openrouteservice.org/v2/directions/driving-car/geojson', [
            'coordinates' => [
                [(float)$request->driver_lng, (float)$request->driver_lat], // dari driver
                [(float)$order->start_lng, (float)$order->start_lat]        // ke jemput
            ]
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Order diterima, tapi gagal hitung rute'], 200);
        }

        $geojson = $response->json()['features'][0]['geometry'];

        return response()->json([
            'message' => 'Order berhasil diterima',
            'route_geojson' => $geojson
        ]);
    }




    // Menampilkan detail order (untuk polling status dari user)
    public function show($id)
    {
        $user = auth()->user();
        \Log::info('🔍 Auth user saat polling:', ['id' => $user->id ?? null, 'level' => $user->level_id ?? null]);

        $order = Order::with(['driver','user']) // pastikan relasi driver->user tersedia
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                if ($user->level_id == 3) {
                    $query->where('user_id', $user->id); // customer hanya bisa lihat order dia
                } elseif ($user->level_id == 2) {
                    $query->where('driver_id', $user->id); // driver bisa lihat order yang ditugaskan ke dia
                }
            })
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        return response()->json(['data' => $order]);
    }

    
    public function updateStatus(Request $request, $id)
{
    $user = auth()->user();

    if (!$user || $user->level_id != 2) {
        return response()->json(['message' => 'Hanya driver yang bisa update status'], 403);
    }

    $request->validate([
        'status' => 'required|string'
    ]);

    $order = Order::where('id', $id)
        ->where('driver_id', $user->id)
        ->first();

    if (!$order) {
        return response()->json(['message' => 'Order tidak ditemukan'], 404);
    }

    $order->status = $request->status;
    $order->save();

    return response()->json([
        'message' => '✅ Status order berhasil diperbarui',
        'status' => $order->status,
    ]);
}

public function customerUpdateStatus(Request $request, $id)
{
    $user = auth()->user();

    if (!$user || $user->level_id != 3) {
        return response()->json(['message' => 'Hanya customer yang bisa update status ini'], 403);
    }

    $order = Order::where('id', $id)
        ->where('user_id', $user->id)
        ->first();

    if (!$order) {
        return response()->json(['message' => 'Order tidak ditemukan'], 404);
    }

    $request->validate([
        'status' => 'required|string'
    ]);

    $order->status = $request->status;
    $order->save();

    return response()->json([
        'message' => '✅ Status order diperbarui oleh customer',
        'status' => $order->status
    ]);
}


    public function routeToDestination($id)
{
    $order = Order::find($id);

    if (!$order) {
        return response()->json(['message' => 'Order tidak ditemukan'], 404);
    }

    $response = Http::withHeaders([
        'Authorization' => env('ORS_API_KEY'),
        'Content-Type' => 'application/json'
    ])->post('https://api.openrouteservice.org/v2/directions/driving-car/geojson', [
        'coordinates' => [
            [(float)$order->start_lng, (float)$order->start_lat],
            [(float)$order->dest_lng, (float)$order->dest_lat]
        ]
    ]);

    if ($response->failed()) {
        return response()->json(['message' => 'Gagal hitung rute ke tujuan'], 500);
    }

    return response()->json([
        'message' => 'Rute ke tujuan berhasil dihitung',
        'route_geojson' => $response->json()['features'][0]['geometry']
    ]);
}


}
