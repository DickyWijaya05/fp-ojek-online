<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\DriverRating;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $user = auth()->user();

        // Validasi order milik user ini
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', $user->id)
                      ->first();

        if (!$order) {
            return response()->json(['message' => 'Order tidak valid atau bukan milik Anda'], 403);
        }

        // Cegah rating ganda untuk order yang sama
        $existing = DriverRating::where('user_id', $user->id)
                                ->where('order_id', $request->order_id)
                                ->first();

        if ($existing) {
            return response()->json(['message' => 'Kamu sudah memberi rating untuk order ini'], 409);
        }

         $driver = Driver::where('user_id', $order->driver_id)->first();

        if (!$driver) {
            return response()->json(['message' => 'Driver tidak ditemukan di tabel drivers'], 404);
        }

        // Simpan rating baru
        DriverRating::create([
            'driver_id' => $driver->id,
            'user_id' => $user->id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Hitung ulang rata-rata
        $avg = DriverRating::where('driver_id', $driver->id)->avg('rating');

        // Simpan ke tabel driver
        Driver::where('id', $driver->id)->update([
            'rating' => round($avg, 2),
            'updated_at' => now()
        ]);

        return response()->json(['message' => '✅ Rating berhasil disimpan']);
    }
}
