<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifConfig extends Model
{
    protected $table = 'tarif_configs'; // pastikan sesuai dengan nama tabel di database

    protected $fillable = [
        'price_per_km',
    ];

    public $timestamps = true; // atau false jika tidak pakai created_at dan updated_at
}
