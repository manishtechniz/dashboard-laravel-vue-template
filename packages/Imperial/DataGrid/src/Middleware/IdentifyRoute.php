<?php

namespace Imperial\DataGrid\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class IdentifyRoute
{
    public function __construct() {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try { 
            $route = Route::getRoutes()->getByName(request('current_route_name'));

            // dd($route, request('current_route_name'));
            $middlewares = $route->middleware();

            $activeGuard = null;

            foreach ($middlewares as $middleware) {
                if (Str::startsWith($middleware, 'auth:')) {
                    $activeGuard = explode(':', $middleware)[1];

                    break;
                } else if (Str::startsWith($middleware, 'auth')) {
                    $activeGuard = config('auth.defaults.guard');

                    break;
                }
            }
        } catch (\Throwable $th) {
            // dd($th->getMessage()); 
            abort(404, 'Route not found.');
        }

        if (Auth::guard($activeGuard)->check()) {
            $authUser = Auth::guard($activeGuard)->user() ?? null;

            $request->attributes->add([
                'user_id' => $authUser->id 
            ]);

            return $next($request); 
        } 
        
        abort(404, 'Route not found'); 
    }
}
