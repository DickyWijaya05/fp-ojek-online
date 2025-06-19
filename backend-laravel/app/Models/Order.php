<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'driver_id',
        'start_lat',
        'start_lng',
        'dest_lat',
        'dest_lng',
        'distance_km',
        'fare',
        'status',
    ];
}
