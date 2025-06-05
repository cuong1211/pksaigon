<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HtmlCacheMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ cache GET requests
        if ($request->method() !== 'GET' || $request->user()) {
            return $next($request);
        }

        // Không cache admin routes
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $next($request);
        }

        // Tạo cache key
        $cacheKey = 'html_page_' . md5($request->fullUrl());

        // Kiểm tra cache
        if (Cache::has($cacheKey)) {
            $cachedContent = Cache::get($cacheKey);
            return response($cachedContent)
                ->header('X-Cache', 'HIT')
                ->header('X-Cache-Key', $cacheKey);
        }

        $response = $next($request);

        // Cache successful HTML responses
        if (
            $response->status() === 200 &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html')
        ) {
            // Cache for 1 hour
            Cache::put($cacheKey, $response->getContent(), 3600);
            $response->headers->set('X-Cache', 'MISS');
            $response->headers->set('X-Cache-Key', $cacheKey);
        }

        return $response;
    }
}
