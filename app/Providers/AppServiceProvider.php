<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Loại bỏ /public/ khỏi URL
        URL::forceRootUrl(config('app.url'));
        
        // Cấu hình public path
        $this->app->bind('path.public', function() {
            return base_path();
        });
    }
}