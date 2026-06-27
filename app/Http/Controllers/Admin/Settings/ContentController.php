<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Affiche la page de gestion du contenu du site
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key');

        // Valeurs par défaut pour chaque page
        $defaults = $this->getDefaults();

        return view('admin.settings.content.index', compact('settings', 'defaults'));
    }

    /**
     * Met à jour le contenu du site
     */
    public function update(Request $request)
    {
        $validated = $request->validate($this->getValidationRules());

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        clear_settings_cache();

        return back()->with('success', 'Contenu du site mis à jour avec succès');
    }

    /**
     * Retourne les valeurs par défaut pour chaque clé
     */
    private function getDefaults(): array
    {
        return [
            // Page d'accueil - Hero
            'home_hero_title_line1' => 'DECOUVREZ DES EXPERIENCES',
            'home_hero_title_line2' => 'INOUBLIABLES ET DES',
            'home_hero_subtitle' => 'EVENEMENTS SPECTACULAIRES',
            'home_search_placeholder' => 'Rechercher un evenement, une categorie...',

            // Page d'accueil - Section mise en avant
            'home_featured_label' => 'A ne pas manquer',
            'home_featured_title' => 'Top des evenements',
            'home_voir_plus_label' => 'Voir plus',

            // Page d'accueil - Statistiques
            'home_stats_events_label' => 'Evenements',
            'home_stats_categories_label' => 'Categories',
            'home_stats_participants_label' => 'Participants',

            // Page liste événements
            'events_list_title' => 'Tous les evenements',
            'events_list_intro' => 'Decouvrez tous les evenements a Lome et au Togo',

            // Page détail événement
            'event_participate_label' => 'Participer',
            'event_buy_now_label' => 'Acheter maintenant',
            'event_complete_label' => 'Evenement complet',
            'event_full_label' => 'Complet',
            'event_free_label' => 'Gratuit',

            // Footer
            'footer_newsletter_title' => 'Newsletter',
            'footer_newsletter_text' => 'Abonnez-vous pour recevoir nos dernieres actualites',
            'footer_newsletter_placeholder' => 'Votre email',
            'footer_newsletter_button' => "S'abonner",
            'footer_copyright' => 'Tous droits reserves',
            'footer_agency_name' => 'Eledji',

            // Pages auth
            'auth_login_title' => 'Bienvenue',
            'auth_login_subtitle' => 'Connectez-vous pour acceder a votre compte',
            'auth_register_title' => 'Bienvenue',
            'auth_register_subtitle' => 'Creez un compte pour commencer',
        ];
    }

    /**
     * Retourne les règles de validation pour chaque champ
     */
    private function getValidationRules(): array
    {
        return [
            // Page d'accueil - Hero
            'home_hero_title_line1' => 'required|string|max:255',
            'home_hero_title_line2' => 'required|string|max:255',
            'home_hero_subtitle' => 'required|string|max:255',
            'home_search_placeholder' => 'required|string|max:255',

            // Page d'accueil - Section mise en avant
            'home_featured_label' => 'required|string|max:255',
            'home_featured_title' => 'required|string|max:255',
            'home_voir_plus_label' => 'required|string|max:100',

            // Page d'accueil - Statistiques
            'home_stats_events_label' => 'required|string|max:100',
            'home_stats_categories_label' => 'required|string|max:100',
            'home_stats_participants_label' => 'required|string|max:100',

            // Page liste événements
            'events_list_title' => 'required|string|max:255',
            'events_list_intro' => 'nullable|string|max:500',

            // Page détail événement
            'event_participate_label' => 'required|string|max:100',
            'event_buy_now_label' => 'required|string|max:100',
            'event_complete_label' => 'required|string|max:255',
            'event_full_label' => 'required|string|max:50',
            'event_free_label' => 'required|string|max:50',

            // Footer
            'footer_newsletter_title' => 'required|string|max:100',
            'footer_newsletter_text' => 'nullable|string|max:500',
            'footer_newsletter_placeholder' => 'required|string|max:255',
            'footer_newsletter_button' => 'required|string|max:100',
            'footer_copyright' => 'required|string|max:255',
            'footer_agency_name' => 'required|string|max:100',

            // Pages auth
            'auth_login_title' => 'required|string|max:255',
            'auth_login_subtitle' => 'nullable|string|max:500',
            'auth_register_title' => 'required|string|max:255',
            'auth_register_subtitle' => 'nullable|string|max:500',
        ];
    }
}