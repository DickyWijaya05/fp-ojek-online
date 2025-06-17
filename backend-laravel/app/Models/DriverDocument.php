<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'ktp',
        'selfie_ktp',
        'sim',
        'stnk',
        'pas_photo',
        'vehicle_photo',
        'vehicle_type',
        'vehicle_name',
        'status',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}

