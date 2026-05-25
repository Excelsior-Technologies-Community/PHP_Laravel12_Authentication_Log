<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class TrackDevice
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        if (auth()->check()) {
            $deviceId = md5($request->userAgent() . $request->ip());
            
            // Store device info in session
            session(['device_id' => $deviceId]);
            
            // Set cookie for device tracking (1 year)
            Cookie::queue('device_id', $deviceId, 525600);
        }
        
        return $response;
    }
}