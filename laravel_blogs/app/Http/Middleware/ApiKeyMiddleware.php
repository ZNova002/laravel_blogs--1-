<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        if ($apiKey !== env('API_KEY')) {
            return response()->json(['success' => false, 'message' => 'Khóa API không hợp lệ.'], 401);
        }
        return $next($request);
    }
}
