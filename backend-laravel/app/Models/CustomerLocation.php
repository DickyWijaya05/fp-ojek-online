<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLocation extends Model
{
    protected $fillable = [
        'customer_id',
        'start_address',
        'start_lat',
        'start_lng',
        'dest_address',
        'dest_lat',
        'dest_lng',
        'distance_km',
        'duration_min',
        'total_price',
    ];
}

