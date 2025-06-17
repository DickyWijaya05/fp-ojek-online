<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsDriver
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->level_id == 2) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Unauthorized. Hanya driver yang dapat mengakses.'
        ], 403);
    }
}
