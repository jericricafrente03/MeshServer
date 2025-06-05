<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowOnlyFromVpnIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIp = env('VPN_SERVER_IP');
        $clientIp = $request->ip();

        if ($clientIp !== $allowedIp) {
            abort(403, 'Access denied.');
        }
        
        return $next($request);
    }
}
