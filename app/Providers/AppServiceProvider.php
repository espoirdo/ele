<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\UpdateLastLogin;
use App\Services\PzgateService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PzgateService::class, fn() => new PzgateService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer le listener pour mettre à jour last_login_at
        Event::listen(Login::class, UpdateLastLogin::class);

        // Synchroniser les fichiers de storage/app/public vers public/storage
        $this->syncStorageFiles();
    }

    /**
     * Synchronise les fichiers de storage/app/public vers public/storage
     * pour garantir l'accès aux images via le navigateur
     */
    private function syncStorageFiles(): void
    {
        $sourceBase = storage_path('app/public');
        $targetBase = public_path('storage');

        // Créer le dossier target s'il n'existe pas
        if (!File::exists($targetBase)) {
            File::makeDirectory($targetBase, 0755, true);
        }

        // Mapper les dossiers sources vers les dossiers cibles
        // Les fichiers sont stockés dans settings/logos, settings/hero, events, etc.
        $mappings = [
            'events' => 'events',
            'settings/logos' => 'settings/logos',
            'settings/hero' => 'settings/hero',
            'tickets' => 'tickets',
        ];

        foreach ($mappings as $sourceSubDir => $targetSubDir) {
            $sourcePath = $sourceBase . '/' . $sourceSubDir;
            $targetPath = $targetBase . '/' . $targetSubDir;

            if (File::exists($sourcePath)) {
                // Créer le sous-dossier cible
                if (!File::exists($targetPath)) {
                    File::makeDirectory($targetPath, 0755, true);
                }

                // Copier les fichiers manquants
                $sourceFiles = File::files($sourcePath);
                foreach ($sourceFiles as $file) {
                    $targetFile = $targetPath . '/' . $file->getFilename();
                    if (!File::exists($targetFile)) {
                        File::copy($file->getPathname(), $targetFile);
                    }
                }
            }
        }
    }
}

// Déclaration unique de la fonction setting
if (!function_exists('setting')) {
    function setting($key = null, $default = null) {
        // Utiliser Setting::get pour toujours avoir la dernière valeur
        if ($key === null) {
            return \App\Models\Setting::pluck('value', 'key')->all();
        }

        return \App\Models\Setting::get($key, $default);
    }
}
