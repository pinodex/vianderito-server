<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RequirePasswordChange
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
        if ($request->user() && $request->user()->require_password_change) {
            return redirect()->route('dashboard.profile.password');
        }

        return $next($request);
    }
}
