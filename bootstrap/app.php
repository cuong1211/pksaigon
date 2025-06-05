<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\GzipMiddleware;
use App\Http\Middleware\HtmlCacheMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Đăng ký middleware alias
        $middleware->web(append: [
            GzipMiddleware::class,
            HtmlCacheMiddleware::class,
        ]);
        $middleware->alias([
            'check.auth' => \App\Http\Middleware\CheckAuth::class,
            'vietqr.auth' => \App\Http\Middleware\VietQRAuth::class,
            'gzip' => GzipMiddleware::class,
            'html.cache' => HtmlCacheMiddleware::class,
        ]);
    })
    ->withProviders([
        App\Providers\SEOServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
