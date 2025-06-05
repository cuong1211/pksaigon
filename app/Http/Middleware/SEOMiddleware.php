<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SEOMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Thêm SEO headers
        if ($response instanceof \Illuminate\Http\Response) {
            // Security headers
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
            $response->headers->set('X-XSS-Protection', '1; mode=block');
            $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

            // SEO friendly headers
            $response->headers->set('X-Robots-Tag', 'index, follow');

            // Performance headers
            if (!$response->headers->has('Cache-Control')) {
                if (str_contains($request->path(), 'css') || str_contains($request->path(), 'js')) {
                    $response->headers->set('Cache-Control', 'public, max-age=31536000'); // 1 year
                } elseif (str_contains($request->path(), 'images')) {
                    $response->headers->set('Cache-Control', 'public, max-age=2592000'); // 30 days
                } else {
                    $response->headers->set('Cache-Control', 'public, max-age=300'); // 5 minutes
                }
            }
        }

        return $response;
    }
}
