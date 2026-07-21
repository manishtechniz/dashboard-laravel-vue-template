<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\Sanctum;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth:admin'])
                ->prefix('admin')
                ->name('admin.')->group(base_path('routes/admin.php'));
                
            Route::middleware(['web', 'auth'])
                ->group(base_path('routes/web.php'));    
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Redirect guest user to login page for web/admin, return null for API.
        $middleware->redirectGuestsTo(function (Request $request) { 
            if ($request->is('api/*') || $request->expectsJson()) {
                return 'api error';
            }

            session(['redirectTo' => url()->full()]);
            if ($request->routeIs('admin.*') && ! Auth::guard('admin')->check()) {
                return route('admin.login');
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated.'
                ], 401);
            }
        });
    })
    ->create();
