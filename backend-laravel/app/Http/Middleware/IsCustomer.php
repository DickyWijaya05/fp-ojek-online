<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->level_id == 3) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Unauthorized. Hanya customer yang dapat mengakses.'
        ], 403);
    }
}
