<?php

namespace App\Http\Middleware;

use Closure;

class KioskKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->bearerToken() != config('kiosk.key')) {
            abort(401);
        }

        return $next($request);
    }
}
