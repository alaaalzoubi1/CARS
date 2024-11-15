<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRoleMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('admin')) {
                return $next($request);
            }
        }
        abort(403,"Unauthenticated  ");
    }
}
