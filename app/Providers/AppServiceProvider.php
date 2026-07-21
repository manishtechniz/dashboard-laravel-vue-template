<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang/admin', 'admin');

        Blade::anonymousComponentPath(
            __DIR__.'/../../resources/views/components/admin',
            'admin'
        );

        $this->loadViewsFrom(
            __DIR__.'/../../resources/views/admin',
            'admin'
        );
    }

    public function registerHelpers(): void
    {
        foreach (glob(__DIR__.'/../Http/Helpers/*.php') as $file) {
            require_once $file;
        }
    }
}
