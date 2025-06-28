<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi jamak Laravel (opsional, tapi eksplisit lebih baik)
    protected $table = 'drivers';

    // Kolom-kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'user_id',
        'foto_profil',
        'alamat',
        'status',
        'rating',
        'jenis_kelamin',     
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
