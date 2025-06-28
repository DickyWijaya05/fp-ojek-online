<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'ktp',
        'selfie_ktp',
        'sim',
        'stnk',
        'pas_photo',
        'vehicle_photo',
        'vehicle_name',
        'vehicle_color', 
        'plate_number',  
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
