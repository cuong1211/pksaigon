<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GzipMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Chỉ nén cho frontend, không nén admin
        if ($this->shouldSkipGzip($request)) {
            return $response;
        }

        // Kiểm tra browser có hỗ trợ gzip không
        $acceptEncoding = $request->header('Accept-Encoding', '');
        if (!str_contains($acceptEncoding, 'gzip')) {
            return $response;
        }

        // Chỉ nén content HTML, CSS, JS
        $contentType = $response->headers->get('Content-Type', '');
        if (!$this->shouldCompress($contentType)) {
            return $response;
        }

        $content = $response->getContent();
        
        // Chỉ nén nếu content đủ lớn (> 1KB)
        if (strlen($content) < 1024) {
            return $response;
        }

        // Nén content
        $compressedContent = gzencode($content, 6); // Level 6 cho tốc độ tối ưu
        
        if ($compressedContent !== false) {
            $response->setContent($compressedContent);
            $response->headers->set('Content-Encoding', 'gzip');
            $response->headers->set('Content-Length', strlen($compressedContent));
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        return $response;
    }

    /**
     * Xác định có nên bỏ qua gzip không
     */
    private function shouldSkipGzip(Request $request): bool
    {
        // Bỏ qua gzip cho admin để tránh xung đột
        if ($request->is('admin/*') || $request->is('admin')) {
            return true;
        }

        // Bỏ qua cho API responses
        if ($request->is('api/*') || $request->wantsJson()) {
            return true;
        }

        return false;
    }

    /**
     * Xác định content type có nên compress không
     */
    private function shouldCompress(string $contentType): bool
    {
        $compressibleTypes = [
            'text/html',
            'text/css',
            'text/javascript',
            'application/javascript',
            'application/json',
            'text/xml',
            'application/xml',
            'text/plain'
        ];

        foreach ($compressibleTypes as $type) {
            if (str_contains($contentType, $type)) {
                return true;
            }
        }

        return false;
    }
}