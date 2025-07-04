<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Order;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('DATA MASUK TRANSAKSI:', $request->all());

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_id' => 'required|exists:users,id',
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
            'total_price' => 'required|numeric',
            'metode' => 'required|in:tunai,qris',

        ]);

        // ✅ Ambil order untuk ambil driver_id yang valid
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // ✅ Simpan transaksi dengan driver_id dari order
        $transaction = Transaction::create([
            'order_id' => $order->id,
            'customer_id' => $request->customer_id,
            'driver_id' => $order->driver_id, // ✅ FIX PENTING
            'distance' => $request->distance,
            'duration' => $request->duration,
            'total_price' => $request->total_price,
            'metode' => $request->metode,
        ]);

        return response()->json([
            'message' => '✅ Transaksi berhasil disimpan.',
            'data' => $transaction,
        ]);
    }

    // Tambahan opsional: lihat semua transaksi
    public function index(Request $request)
    {
        $user = $request->user(); // jika pakai sanctum dan user login
        $data = Transaction::with(['order', 'driver', 'customer'])
            ->when($user, fn($q) => $q->where('customer_id', $user->id)) // filter jika user login
            ->latest()
            ->get()
            ->map(function ($trx) {
                return [
                    'id' => $trx->id,
                    'total_price' => $trx->total_price,
                    'distance' => $trx->distance,
                    'duration' => $trx->duration,
                    'created_at' => $trx->created_at->toDateTimeString(),

                    // Data dari relasi order
                    'start_address' => optional($trx->order)->start_address,
                    'dest_address' => optional($trx->order)->dest_address,

                    // Tambahan jika perlu
                    'driver_name' => optional($trx->driver?->user)->name ?? null,
                    'customer_name' => optional($trx->customer)->name ?? null,
                ];
            });

        return response()->json([
            'data' => $data,
        ]);
    }


    public function driverTransactions(Request $request)
    {
        $driver = $request->user(); // user yang login (driver)

        $data = Transaction::with(['order', 'customer']) // relasi ke customer
            ->where('driver_id', $driver->id)
            ->latest()
            ->get()
            ->map(function ($trx) {
                return [
                    'id' => $trx->id,
                    'total_price' => $trx->total_price,
                    'distance' => $trx->distance,
                    'duration' => $trx->duration,
                    'created_at' => $trx->created_at->toDateTimeString(),
                    'start_address' => optional($trx->order)->start_address,
                    'dest_address' => optional($trx->order)->dest_address,
                    'customer_name' => optional($trx->customer)->name ?? null,
                ];
            });

        return response()->json([
            'data' => $data
        ]);
    }

    public function getPaymentDetails($orderId)
    {
        $user = auth()->user();

        $transaction = \App\Models\Transaction::with('driverProfile')
            ->where('order_id', $orderId)
            ->when($user->level_id == 3, fn($q) => $q->where('customer_id', $user->id))
            ->when($user->level_id == 2, fn($q) => $q->where('driver_id', $user->id))
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json([
            'total_price' => $transaction->total_price,
            'metode' => $transaction->metode,
        ]);
    }
    // DriverController.php (atau bisa gabung di TransactionController kalau kamu mau)

    public function getDriverProfileByOrder($orderId)
    {
        $order = \App\Models\Order::find($orderId);
        if (!$order) {
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        $driver = \App\Models\Driver::where('user_id', $order->driver_id)->with('user')->first();

        if (!$driver) {
            return response()->json(['message' => 'Profil driver tidak ditemukan'], 404);
        }

        return response()->json([
            'driver_name' => $driver->user->name ?? 'Driver',
            'driver_photo' => $driver->foto_profil
                ? asset('storage/profiles/' . $driver->foto_profil)
                : null
        ]);
    }


}
