<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'level_id', 'activity', 'ip_address', 'device',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
