<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PremiumOption;
use App\Models\PremiumPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventCreateController extends Controller
{
    /**
     * Display step 1 - General Information
     */
    public function showStep1()
    {
        $categories = Category::all();
        $data = session('event_step1', []);

        return view('events.create.step1', compact('categories', 'data'));
    }

    /**
     * Process step 1 - General Information
     */
    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:150',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'required|string|min:50',
        ]);

        session(['event_step1' => $validated]);

        return redirect()->route('events.create.step2');
    }

    /**
     * Display step 2 - Location, Date and Time
     */
    public function showStep2()
    {
        if (!session()->has('event_step1')) {
            return redirect()->route('events.create.step1');
        }

        $categories = Category::all();
        $data = session('event_step2', []);

        return view('events.create.step2', compact('categories', 'data'));
    }

    /**
     * Process step 2 - Location, Date and Time
     */
    public function postStep2(Request $request)
    {
        if (!session()->has('event_step1')) {
            return redirect()->route('events.create.step2');
        }

        $validated = $request->validate([
            'lieu' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        session(['event_step2' => $validated]);

        return redirect()->route('events.create.step3');
    }

    /**
     * Display step 3 - Tickets and Pricing
     */
    public function showStep3()
    {
        if (!session()->has('event_step2')) {
            return redirect()->route('events.create.step2');
        }

        $step3 = session('event_step3', []);

        return view('events.create.step3', compact('step3'));
    }

    /**
     * Process step 3 - Tickets and Pricing
     */
    public function postStep3(Request $request)
    {
        if (!session()->has('event_step2')) {
            return redirect()->route('events.create.step2');
        }

        // Validation rules
        $rules = [
            'type_evenement' => 'required|in:gratuit,payant',
        ];

        if ($request->input('type_evenement') === 'payant') {
            $rules['billet_classique_prix'] = 'nullable|numeric|min:0';
            $rules['billet_vip_prix'] = 'nullable|numeric|min:0';
            $rules['billet_vvip_prix'] = 'nullable|numeric|min:0';

            // At least one ticket type must be active
            if (
                !$request->has('billet_classique_actif') &&
                !$request->has('billet_vip_actif') &&
                !$request->has('billet_vvip_actif')
            ) {
                return back()->withErrors([
                    'billets' => 'Vous devez activer au moins un type de billet.'
                ])->withInput();
            }
        }

        $validated = $request->validate($rules);

        // Store data in session
        $sessionData = [
            'type_evenement' => $request->input('type_evenement'),
            'billet_classique_actif' => $request->has('billet_classique_actif') ? true : false,
            'billet_classique_prix' => $request->input('billet_classique_prix', 0),
            'billet_vip_actif' => $request->has('billet_vip_actif') ? true : false,
            'billet_vip_prix' => $request->input('billet_vip_prix', 0),
            'billet_vvip_actif' => $request->has('billet_vvip_actif') ? true : false,
            'billet_vvip_prix' => $request->input('billet_vvip_prix', 0),
        ];

        // If gratuit, all tickets are inactive
        if ($request->input('type_evenement') === 'gratuit') {
            $sessionData['billet_classique_actif'] = false;
            $sessionData['billet_vip_actif'] = false;
            $sessionData['billet_vvip_actif'] = false;
            $sessionData['billet_classique_prix'] = 0;
            $sessionData['billet_vip_prix'] = 0;
            $sessionData['billet_vvip_prix'] = 0;
        }

        session(['event_step3' => $sessionData]);

        return redirect()->route('events.create.step4');
    }

    /**
     * Display step 4 - Media and Publication
     */
    public function showStep4()
    {
        if (!session()->has('event_step3')) {
            return redirect()->route('events.create.step3');
        }

        $step1 = session('event_step1', []);
        $step2 = session('event_step2', []);
        $step3 = session('event_step3', []);

        // Préparer les billets actifs pour le récapitulatif
        $billetsActifs = [];

        if (!empty($step3['billet_classique_actif'])) {
            $billetsActifs[] = [
                'type'  => 'Classique',
                'prix'  => $step3['billet_classique_prix'] ?? 0,
                'color' => '#333333',
            ];
        }
        if (!empty($step3['billet_vip_actif'])) {
            $billetsActifs[] = [
                'type'  => 'VIP',
                'prix'  => $step3['billet_vip_prix'] ?? 0,
                'color' => '#CC0000',
            ];
        }
        if (!empty($step3['billet_vvip_actif'])) {
            $billetsActifs[] = [
                'type'  => 'VVIP',
                'prix'  => $step3['billet_vvip_prix'] ?? 0,
                'color' => '#F5A623',
            ];
        }

        return view('events.create.step4', compact('step1', 'step2', 'step3', 'billetsActifs'));
    }

    /**
     * Process step 4 - Final submission
     */
    public function postStep4(Request $request)
    {
        if (!session()->has('event_step3')) {
            return redirect()->route('events.create.step3');
        }

        $validated = $request->validate([
            'image_couverture' => 'nullable|image|max:5120|mimes:jpg,jpeg,png,webp',
            'options_premium' => 'nullable|array',
            'statut' => 'required|in:brouillon,publie',
        ]);

        // Merge all session data
        $step1 = session('event_step1', []);
        $step2 = session('event_step2', []);
        $step3 = session('event_step3', []);

        $eventData = array_merge($step1, $step2, $step3, $validated);
        $eventData['user_id'] = Auth::id();
        $eventData['slug'] = Str::slug($eventData['titre']);

        // Convert type_evenement to est_gratuit for database compatibility
        if (isset($eventData['type_evenement'])) {
            $eventData['est_gratuit'] = $eventData['type_evenement'] === 'gratuit';
            unset($eventData['type_evenement']);
        }

        // Remove options_premium from eventData - will be handled separately after payment
        $optionsPremium = $eventData['options_premium'] ?? [];
        unset($eventData['options_premium']);

        // Handle image upload
        if ($request->hasFile('image_couverture')) {
            $eventData['image_couverture'] = $request->file('image_couverture')->store('events', 'public');
        }

        // Create the event
        $event = \App\Models\Event::create($eventData);

        // Check if premium options were selected
        if (!empty($optionsPremium)) {
            // Calculate total premium
            $prixOptions = [
                'mise_en_avant'   => setting('premium_mise_en_avant_prix', 5000),
                'newsletter'      => setting('premium_newsletter_prix', 3000),
                'reseaux_sociaux' => setting('premium_reseaux_prix', 2000),
            ];

            $totalPremium = 0;
            foreach ($optionsPremium as $option) {
                $totalPremium += $prixOptions[$option] ?? 0;
            }

            // Store in session for payment page
            session([
                'premium_payment' => [
                    'event_id'       => $event->id,
                    'event_slug'     => $event->slug,
                    'options'        => $optionsPremium,
                    'total'          => $totalPremium,
                    'description'    => 'Options premium Eledji',
                ]
            ]);

            // Clear session but keep event created
            session()->forget(['event_step1', 'event_step2', 'event_step3']);

            // Redirect to existing payment system
            return redirect()->route('payment.show', $event->slug)
                             ->with('info', 'Finalisez le paiement de vos options premium pour booster votre événement.');
        }

        // If no premium options, publication directly
        session()->forget(['event_step1', 'event_step2', 'event_step3']);

        return redirect()->to('/events/' . $event->slug)
            ->with('success', 'Evenement cree avec succes!');
    }
}