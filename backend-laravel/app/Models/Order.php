<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'driver_id',
        'start_lat', 'start_lng',
        'dest_lat', 'dest_lng',
        'start_address', 'dest_address',
        'status',
        'distance_km', 'duration_min', 'total_price',
    ];

    // Relasi ke customer (user yang memesan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke driver (user yang menerima)
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
