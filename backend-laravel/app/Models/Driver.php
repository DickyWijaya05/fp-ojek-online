<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'foto_profil',
        'foto_qris',
        'jenis_kelamin',
        'status',
        'rating',
        'alamat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // app/Models/Driver.php
public function transactions()
{
    return $this->hasMany(\App\Models\Transaction::class, 'driver_id', 'user_id');
}

    
}
