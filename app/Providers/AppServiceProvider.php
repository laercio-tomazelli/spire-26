<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register spire-ui components with 'spire' prefix
        // Usage: <x-spire::button>, <x-spire::input>, etc.
        Blade::anonymousComponentPath(
            resource_path('views/components/ui'),
            'spire',
        );
    }
}
