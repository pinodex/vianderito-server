<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class UseGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $name)
    {
        $request->setUserResolver(function () use ($name) {
            return Auth::guard($name)->user();
        });

        return $next($request);
    }
}
