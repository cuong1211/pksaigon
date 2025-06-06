<?php

// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register AssetHelper class
        $this->app->singleton('AssetHelper', function () {
            return new \App\Helpers\AssetHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Force proper APP_URL in production
        if (config('app.env') === 'production' && !empty(config('app.url'))) {
            URL::forceRootUrl(config('app.url'));
        }

        // Include helper functions file if exists
        if (file_exists(app_path('Helpers/functions.php'))) {
            require_once app_path('Helpers/functions.php');
        }
    }
}
