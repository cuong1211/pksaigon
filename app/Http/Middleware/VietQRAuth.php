<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VietQRAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'error' => true,
                'errorReason' => 'UNAUTHORIZED',
                'toastMessage' => 'Thiếu Bearer Token'
            ], 401);
        }

        $token = substr($authHeader, 7); // Remove "Bearer "
        
        // Kiểm tra token trong cache
        $tokenData = cache()->get("vietqr_token_{$token}");
        
        if (!$tokenData) {
            return response()->json([
                'error' => true,
                'errorReason' => 'INVALID_TOKEN',
                'toastMessage' => 'Token không hợp lệ hoặc đã hết hạn'
            ], 401);
        }

        // Kiểm tra token có hết hạn không
        if ($tokenData['expires_at'] < now()->timestamp) {
            // Xóa token đã hết hạn
            cache()->forget("vietqr_token_{$token}");
            
            return response()->json([
                'error' => true,
                'errorReason' => 'TOKEN_EXPIRED',
                'toastMessage' => 'Token đã hết hạn'
            ], 401);
        }

        // Thêm thông tin token vào request
        $request->merge(['vietqr_token_data' => $tokenData]);
        
        return $next($request);
    }
}