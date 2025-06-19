<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'dest_lat' => 'required|numeric',
            'dest_lng' => 'required|numeric',
            'distance_km' => 'required|numeric',
            'fare' => 'required|numeric',
        ]);

        $order = Order::create($request->all());

        return response()->json(['status' => 'success', 'order' => $order]);
    }

    public function acceptOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'required|exists:users,id',
        ]);

        $order = Order::find($request->order_id);
        $order->driver_id = $request->driver_id;
        $order->status = 'accepted';
        $order->save();

        return response()->json(['status' => 'accepted', 'order' => $order]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:accepted,on_the_way,arrived,in_transit,completed,cancelled',
        ]);

        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->save();

        return response()->json(['status' => 'updated', 'order' => $order]);
    }

    public function getActiveOrder($userId)
    {
        $order = Order::where(function ($query) use ($userId) {
            $query->where('customer_id', $userId)
                  ->orWhere('driver_id', $userId);
        })->whereIn('status', ['pending', 'accepted', 'on_the_way', 'in_transit'])
          ->latest()
          ->first();

        return response()->json(['order' => $order]);
    }
}
