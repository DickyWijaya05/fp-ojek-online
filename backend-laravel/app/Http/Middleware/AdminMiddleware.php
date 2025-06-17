<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->level_id == 1) {
            return $next($request);
        }

        // Untuk API, gunakan response JSON
        return response()->json([
            'message' => 'Unauthorized. Hanya admin yang dapat mengakses.'
        ], 403);
    }
}

