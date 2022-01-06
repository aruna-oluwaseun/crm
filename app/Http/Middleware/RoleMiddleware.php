<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * Usage via role : middleware('role:super-admin')
     * Usage via permission : middleware('role:,view-users')
     */
    public function handle(Request $request, Closure $next, $role = 'ignore', $permission = null)
    {

        if($role != 'ignore' && !$request->user()->hasRole($role)) {
            abort(403);
        }

        if($permission !== null && !$request->user()->can($permission))
        {
            abort(403);
        }

        return $next($request);
    }
}
