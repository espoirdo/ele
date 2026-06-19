<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Helper global setting()
        if (!function_exists('setting')) {
            function setting(string $key, $default = null)
            {
                $cacheKey = 'settings_cache';

                try {
                    $settings = Cache::remember($cacheKey, 3600, function () {
                        return Setting::all()->keyBy('key');
                    });

                    $value = $settings->get($key)?->value;

                    return $value ?? $default;
                } catch (\Exception $e) {
                    return $default;
                }
            }
        }

        // Fonction pour vider le cache après modification
        if (!function_exists('clear_settings_cache')) {
            function clear_settings_cache(): void
            {
                Cache::forget('settings_cache');
            }
        }
    }
}