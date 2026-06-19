<?php

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        $cacheKey = 'settings_cache';
        try {
            $settings = Cache::remember($cacheKey, 3600, function () {
                return Setting::all()->keyBy('key');
            });
            return $settings->get($key)?->value ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('clear_settings_cache')) {
    function clear_settings_cache(): void
    {
        Cache::forget('settings_cache');
    }
}
