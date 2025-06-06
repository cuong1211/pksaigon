<?php

if (!function_exists('storage_url')) {
    function storage_url($path) {
        return asset('storage/' . $path);
    }
}