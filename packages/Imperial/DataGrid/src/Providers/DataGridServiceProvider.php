<?php

namespace Imperial\DataGrid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Imperial\DataGrid\Middleware\IdentifyRoute;

class DataGridServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->registerConfig();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerMiddleware();

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/datagrid.php' => config_path('datagrid.php'),
        ], 'datagrid-config');
    }

    /**
     * Register middleware
     */
    public function registerMiddleware()
    {
        $router = $this->app->make(Router::class);
        
        // Register an alias for route-specific usage
        $router->aliasMiddleware('identifyRoute', IdentifyRoute::class);
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/datagrid.php', 'datagrid'
        );
    }
}
