<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'latitude',
        'longitude',
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }


    public function getLocation($id)
    {
        $location = \App\Models\DriverLocation::where('driver_id', $id)
            ->latest()
            ->first();

        if (!$location) {
            return response()->json(['message' => 'Lokasi tidak ditemukan'], 404);
        }

        return response()->json([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ]);
    }

}


