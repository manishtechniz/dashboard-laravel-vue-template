<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerHelpers();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme('https');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang/admin', 'admin');

        Blade::anonymousComponentPath(
            __DIR__ . '/../../resources/views/components/admin',
            'admin'
        );

        $this->loadViewsFrom(
            __DIR__ . '/../../resources/views/admin',
            'admin'
        );

        $this->prepareMenuAndProvide();
    }

    public function registerHelpers(): void
    {
        foreach (glob(__DIR__ . '/../Http/Helpers/*.php') as $file) {
            require_once $file;
        }
    }

    public function prepareMenuAndProvide()
    {
        View::composer('*', function ($view) {
            // View::composer('admin.layouts.sidebar', function ($view) {
            $user = Auth::guard('admin')->user();

            if (! $user) {
                return $view->with('adminMenu', []);
            }

            // echo "I am top cache";
            // Cache the fully built menu array per role for 1 hour
            $menuItems = Cache::remember("admin_menu_role_{$user->role_id}", 0, function () use ($user) {
                // echo "I am inside cache";
                $items = [];

                $aclConfig = config('acl');

                $permissions = $user->role?->permissions ?? [];

                // echo "<pre>";
                // echo "permissions";
                // echo print_r($permissions);

                foreach ($aclConfig as $module => $groups) {
                    $primaryAction = collect($groups)->firstWhere('sort', 1);

                    // echo "<pre>";
                    // echo "primaryAction";
                    // echo print_r($primaryAction);

                    if (
                        ($primaryAction && (($primaryAction['visibility'] ?? null) != 'hidden') && $permissions && in_array($primaryAction['route'], $permissions))
                        || in_array('*', $permissions)
                    ) {
                        $routeName = is_array($primaryAction['route']) ? $primaryAction['route'][0] : $primaryAction['route'];

                        $items[] = [
                            'icon'  => $primaryAction['icon'],
                            'label'  => $primaryAction['name'],
                            'route' => $routeName,
                            'url'   => Route::has($routeName) ? route($routeName) : '#',
                        ];
                    }
                }

                return $items;
            });

            // echo "I am bottom cache";

            $view->with('adminMenu', $menuItems);
        });
    }
}
