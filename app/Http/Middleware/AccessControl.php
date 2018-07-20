<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $args)
    {
        $guard = Auth::guard('dashboard');

        if (!$guard->check()) {
            abort(401);
        }

        $account = $guard->user();

        $hasPermission = $account->canDo(explode(',', $args));

        if ($hasPermission) {
            return $next($request);
        }

        abort(403);
    }
}
