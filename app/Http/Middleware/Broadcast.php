<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class Broadcast
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
        if (starts_with($request->channel_name, 'private-dashboard')) {
            $request->setUserResolver(function () {
                return Auth::guard('dashboard')->user();
            });
        }

        return $next($request);
    }
}
