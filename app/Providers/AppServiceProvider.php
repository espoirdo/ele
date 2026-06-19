<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
    }
}

// Déclaration unique de la fonction setting
if (!function_exists('setting')) {
    function setting($key = null, $default = null) {
        // Votre logique
        // Si vous utilisez un modèle Settings
        // return \App\Models\Setting::get($key, $default);
        
        // Exemple simple
        static $settings = null;
        if ($settings === null) {
            $settings = \App\Models\Setting::pluck('value', 'key')->all();
        }
        
        if ($key === null) {
            return $settings;
        }
        
        return $settings[$key] ?? $default;
    }
}
