<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard("admin")->check()) {
            $hasPermission = hasPermission(request()->route()->getName());

            if (! $hasPermission && request()->ajax()) {
                return response()->json([
                    'message' => 'You don\'t have permission to access this action.',
                ], 403);
            }

            if (! $hasPermission) {
                abort(403, 'You don\'t have permission to access this action.');
            }
        }

        return $next($request);
    }
}
