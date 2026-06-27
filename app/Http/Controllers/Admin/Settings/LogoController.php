<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogoController extends Controller
{
    /**
     * Affiche la page de gestion des logos
     */
    public function index()
    {
        $logos = $this->getLogosConfig();

        return view('admin.settings.logos.index', compact('logos'));
    }

    /**
     * Met à jour les logos du site
     */
    public function update(Request $request)
    {
        $logos = $this->getLogosConfig();

        foreach ($logos as $key => $config) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);

                // Validation
                $request->validate([
                    $key => 'image|mimes:png,jpg,jpeg,svg,webp|max:2048'
                ]);

                // Supprimer l'ancien fichier si existe
                $oldPath = setting($key);
                if ($oldPath && Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }

                // Stocker le nouveau fichier
                $path = $file->store('settings/logos', 'public');

                // Sauvegarder le chemin dans settings
                Setting::set($key, $path);
            }
        }

        clear_settings_cache();

        return back()->with('success', 'Logos mis a jour avec succes');
    }

    /**
     * Configuration des logos
     */
    private function getLogosConfig(): array
    {
        return [
            'logo_navbar' => [
                'label' => 'Logo principal (Navbar)',
                'description' => 'Logo affiche dans la barre de navigation',
                'current' => setting('logo_navbar', 'logos/eledji-logo.png'),
            ],
            'logo_footer' => [
                'label' => 'Logo Footer',
                'description' => 'Logo affiche dans le pied de page',
                'current' => setting('logo_footer', 'logos/eledji-logo.png'),
            ],
            'logo_tmoney' => [
                'label' => 'Logo TMoney',
                'description' => 'Logo TMoney affiche sur la page de paiement',
                'current' => setting('logo_tmoney', 'logos/tmoney.png'),
            ],
            'logo_flooz' => [
                'label' => 'Logo Flooz',
                'description' => 'Logo Flooz affiche sur la page de paiement',
                'current' => setting('logo_flooz', 'logos/flooz.png'),
            ],
            'logo_carte_bancaire' => [
                'label' => 'Icone Carte Bancaire',
                'description' => 'Icone carte bancaire affiche sur la page de paiement',
                'current' => setting('logo_carte_bancaire', 'logos/carte-bancaire.png'),
            ],
            'image_evenement_defaut' => [
                'label' => 'Image par defaut des evenements',
                'description' => 'Image utilisee quand un evenement na pas dimage de couverture',
                'current' => setting('image_evenement_defaut', 'logos/event-default.jpg'),
            ],
        ];
    }
}