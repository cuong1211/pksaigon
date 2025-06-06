<?php

// app/Helpers/AssetHelper.php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class AssetHelper
{
    /**
     * Get storage URL with automatic environment detection
     * 
     * @param string|null $path
     * @return string
     */
    public static function storageUrl($path = null)
    {
        if (!$path) {
            return self::getStorageBaseUrl();
        }

        // Remove leading slash if exists
        $path = ltrim($path, '/');

        // Get base storage URL
        $baseUrl = self::getStorageBaseUrl();

        return $baseUrl . '/' . $path;
    }

    /**
     * Get correct storage base URL based on environment
     * 
     * @return string
     */
    private static function getStorageBaseUrl()
    {
        // Check if we're in production and need /public prefix
        if (app()->environment('production')) {
            // Test if we need /public prefix
            if (self::needsPublicPrefix()) {
                return url('public/storage');
            }
        }

        // Default Laravel structure
        return url('storage');
    }

    /**
     * Check if server needs /public prefix
     * 
     * @return bool
     */
    private static function needsPublicPrefix()
    {
        // Cache the result in session to avoid repeated checks
        $cacheKey = 'needs_public_prefix';

        if (session()->has($cacheKey)) {
            return session($cacheKey);
        }

        // Simple check: if APP_URL contains current domain and we're in production
        $appUrl = config('app.url');
        $currentDomain = request()->getHost();

        // If domain matches and we can't access storage directly, we need /public
        $needsPrefix = false;

        if (strpos($appUrl, $currentDomain) !== false) {
            // Test if we can access storage without /public
            try {
                $testUrl = url('storage') . '/.gitignore';
                $headers = @get_headers($testUrl, 1);
                $needsPrefix = !($headers && strpos($headers[0], '200') !== false);
            } catch (\Exception $e) {
                $needsPrefix = true;
            }
        }

        // Cache result for this session
        session([$cacheKey => $needsPrefix]);

        return $needsPrefix;
    }

    /**
     * Get image URL with fallback to default
     * 
     * @param string|null $imagePath
     * @param string $defaultType
     * @return string
     */
    public static function getImageUrl($imagePath, $defaultType = 'default')
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            return self::storageUrl($imagePath);
        }

        return self::getDefaultImage($defaultType);
    }

    /**
     * Get default image URL
     * 
     * @param string $type
     * @return string
     */
    public static function getDefaultImage($type = 'default')
    {
        $defaults = [
        'service' => 'images/default-service.png',
            'medicine' => 'images/default-medicine.png',
            'post' => 'images/default-post.jpg',
            'user' => 'images/default-avatar.png',
            'default' => 'images/default.png'
        ];

        $imagePath = $defaults[$type] ?? $defaults['default'];

        // For default images, use same logic as storage
        if (app()->environment('production') && self::needsPublicPrefix()) {
            return url('public/' . $imagePath);
        }

        return url($imagePath);
    }

    /**
     * Clear the cache (for testing)
     */
    public static function clearCache()
    {
        session()->forget('needs_public_prefix');
    }
}
