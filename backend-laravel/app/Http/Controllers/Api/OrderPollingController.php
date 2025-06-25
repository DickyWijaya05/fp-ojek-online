<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderPollingController extends Controller
{
    public function getIncomingOrders(Request $request)
    {
        $driver = Auth::user();

        if (!$driver) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $orders = Order::where('driver_id', $driver->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->get();

        return response()->json(['data' => $orders]);
    }

    public function accept(Request $request)
    {
        $driver = Auth::user();
        if (!$driver) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $orderId = $request->input('order_id');
        $order = Order::where('id', $orderId)
                      ->where('driver_id', $driver->id)
                      ->where('status', 'pending')
                      ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or already handled'], 404);
        }

        $order->status = 'accepted';
        $order->save();

        return response()->json(['message' => 'Order accepted']);
    }

    public function reject(Request $request)
    {
        $driver = Auth::user();
        if (!$driver) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $orderId = $request->input('order_id');
        $order = Order::where('id', $orderId)
                      ->where('driver_id', $driver->id)
                      ->where('status', 'pending')
                      ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found or already handled'], 404);
        }

        $order->status = 'rejected';
        $order->save();

        return response()->json(['message' => 'Order rejected']);
    }
}
