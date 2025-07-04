<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverRating extends Model
{
    protected $fillable = [
        'driver_id', 'user_id', 'order_id', 'rating', 'comment'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
