<?php

namespace Modules\Platform\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanManagePlatform
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ( auth()->user()->permission != 'admin' ) {
            abort(403);
        }
        return $next($request);
    }
}
