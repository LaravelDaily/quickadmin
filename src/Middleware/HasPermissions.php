<?php

namespace Laraveldaily\Quickadmin\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class HasPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() != null && $request->user()->permissionCan($request)) {
            return $next($request);
        }

        abort(403);

        return false;
    }
}
