<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HtmlCacheMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // QUAN TRỌNG: Bỏ qua cache cho các route admin và authenticated
        if ($this->shouldSkipCache($request)) {
            return $next($request);
        }

        // Chỉ cache cho GET requests
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        // Tạo cache key
        $cacheKey = $this->generateCacheKey($request);
        
        // Kiểm tra cache có tồn tại không
        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);
            
            return response($cachedResponse['content'])
                ->withHeaders($cachedResponse['headers'])
                ->header('X-Cache', 'HIT')
                ->header('Cache-Control', 'public, max-age=3600');
        }

        $response = $next($request);

        // Chỉ cache response thành công và HTML
        if ($response->isSuccessful() && 
            str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            
            $cacheData = [
                'content' => $response->getContent(),
                'headers' => [
                    'Content-Type' => $response->headers->get('Content-Type'),
                    'X-Cache' => 'MISS'
                ]
            ];

            // Cache trong 1 giờ
            Cache::put($cacheKey, $cacheData, 3600);
            
            $response->header('X-Cache', 'MISS');
        }

        return $response->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Xác định có nên bỏ qua cache không
     */
    private function shouldSkipCache(Request $request): bool
    {
        // Bỏ qua cache cho:
        // 1. Tất cả routes admin
        if ($request->is('admin/*') || $request->is('admin')) {
            return true;
        }

        // 2. Routes authentication
        if ($request->is('login') || $request->is('logout') || $request->is('register')) {
            return true;
        }

        // 3. API routes
        if ($request->is('api/*')) {
            return true;
        }

        // 4. Routes có session (user đã login)
        if ($request->user()) {
            return true;
        }

        // 5. Routes có query parameters động
        if ($request->query('search') || $request->query('filter') || $request->query('page')) {
            return true;
        }

        // 6. POST, PUT, DELETE requests
        if (!$request->isMethod('GET')) {
            return true;
        }

        // 7. Requests có CSRF token (form submissions)
        if ($request->hasHeader('X-CSRF-TOKEN') || $request->has('_token')) {
            return true;
        }

        // 8. AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return true;
        }

        return false;
    }

    /**
     * Tạo cache key unique
     */
    private function generateCacheKey(Request $request): string
    {
        $url = $request->fullUrl();
        $userAgent = $request->userAgent();
        $isMobile = str_contains(strtolower($userAgent), 'mobile') ? 'mobile' : 'desktop';
        
        return 'html_cache:' . md5($url . $isMobile);
    }
}