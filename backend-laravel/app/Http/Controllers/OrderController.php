<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;


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

        $order->status = 'accepted';
            $order->driver_lat = $request->driver_lat; // ⬅️ ini ditambah
            $order->driver_lng = $request->driver_lng;
            $order->save();

        return response()->json(['message' => 'Order berhasil diterima']);
    }



    // Menampilkan detail order (untuk polling status dari user)
    public function show($id)
    {
        $user = auth()->user();

        $order = Order::with(['driver.user']) // pastikan relasi driver->user tersedia
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

}
