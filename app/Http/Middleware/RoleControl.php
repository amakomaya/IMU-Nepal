<?php

namespace App\Http\Middleware;

use Closure;

class RoleControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = explode('|', $role);

        if(!in_array($request->user()->role, $roles)) {
            abort(401);
        }

        return $next($request);
    }
}
