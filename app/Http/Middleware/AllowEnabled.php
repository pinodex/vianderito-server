<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AllowEnabled
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
        $guard = Auth::guard('admin');

        if (!$guard->check()) {
            abort(401);
        }

        $account = $guard->user();

        if (!$account->is_enabled) {
            abort(403);
        }

        return $next($request);
    }
}
