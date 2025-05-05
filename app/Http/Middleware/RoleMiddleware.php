<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $role  The role(s) required to access the route
     * @return mixed
     */
    public function handle(Request $request, $role)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        // Check if the user has the required role
        if (!Auth::user()->hasRole($role)) {
            abort(403, 'Unauthorized');
        }

        return $request;
    }
}
