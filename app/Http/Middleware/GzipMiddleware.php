<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GzipMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Chỉ compress HTML responses
        if (
            $response instanceof \Illuminate\Http\Response &&
            str_contains($request->header('Accept-Encoding', ''), 'gzip') &&
            function_exists('gzencode') &&
            str_contains($response->headers->get('Content-Type', ''), 'text/html')
        ) {
            $content = $response->getContent();

            if (strlen($content) > 1024) { // Chỉ compress nếu > 1KB
                $compressed = gzencode($content, 6); // Level 6 balance giữa speed và compression

                if ($compressed !== false && strlen($compressed) < strlen($content)) {
                    $response->setContent($compressed);
                    $response->headers->set('Content-Encoding', 'gzip');
                    $response->headers->set('Content-Length', strlen($compressed));
                    $response->headers->set('Vary', 'Accept-Encoding');
                }
            }
        }

        return $response;
    }
}
