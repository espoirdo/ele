<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PremiumOption;
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

        $data = session('event_step3', []);

        return view('events.create.step3', compact('data'));
    }

    /**
     * Process step 3 - Tickets and Pricing
     */
    public function postStep3(Request $request)
    {
        if (!session()->has('event_step2')) {
            return redirect()->route('events.create.step2');
        }

        // Validate that at least one ticket type is active
        $validated = $request->validate([
            'est_gratuit' => 'required|boolean',
            'billet_classique_actif' => 'nullable|boolean',
            'billet_classique_prix' => 'nullable|numeric|min:0',
            'billet_vip_actif' => 'nullable|boolean',
            'billet_vip_prix' => 'nullable|numeric|min:0',
            'billet_vvip_actif' => 'nullable|boolean',
            'billet_vvip_prix' => 'nullable|numeric|min:0',
        ]);

        // Check if at least one ticket type is active
        $hasClassique = filter_var($validated['billet_classique_actif'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $hasVip = filter_var($validated['billet_vip_actif'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $hasVvip = filter_var($validated['billet_vvip_actif'] ?? false, FILTER_VALIDATE_BOOLEAN);

        if (!$hasClassique && !$hasVip && !$hasVvip) {
            return back()->withErrors(['billet_classique_actif' => 'Vous devez activer au moins un type de billet.']);
        }

        // Validate prices for active ticket types
        if ($hasClassique && ($validated['billet_classique_prix'] ?? 0) < 0) {
            return back()->withErrors(['billet_classique_prix' => 'Le prix doit être positif ou nul.']);
        }
        if ($hasVip && ($validated['billet_vip_prix'] ?? 0) < 0) {
            return back()->withErrors(['billet_vip_prix' => 'Le prix doit être positif ou nul.']);
        }
        if ($hasVvip && ($validated['billet_vvip_prix'] ?? 0) < 0) {
            return back()->withErrors(['billet_vvip_prix' => 'Le prix doit être positif ou nul.']);
        }

        // Convert string booleans to actual booleans
        $validated['billet_classique_actif'] = $hasClassique;
        $validated['billet_vip_actif'] = $hasVip;
        $validated['billet_vvip_actif'] = $hasVvip;

        // Determine if event is free (all active tickets are free)
        $isFree = true;
        if ($hasClassique && ($validated['billet_classique_prix'] ?? 0) > 0) $isFree = false;
        if ($hasVip && ($validated['billet_vip_prix'] ?? 0) > 0) $isFree = false;
        if ($hasVvip && ($validated['billet_vvip_prix'] ?? 0) > 0) $isFree = false;

        $validated['est_gratuit'] = $isFree;

        session(['event_step3' => $validated]);

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

        $premiumOptions = PremiumOption::all();
        $step1 = session('event_step1', []);
        $step2 = session('event_step2', []);
        $step3 = session('event_step3', []);

        return view('events.create.step4', compact('premiumOptions', 'step1', 'step2', 'step3'));
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
            'premium_mise_en_avant' => 'nullable|boolean',
            'premium_newsletter' => 'nullable|boolean',
            'premium_reseaux' => 'nullable|boolean',
            'statut' => 'required|in:brouillon,publie',
        ]);

        // Merge all session data
        $step1 = session('event_step1', []);
        $step2 = session('event_step2', []);
        $step3 = session('event_step3', []);

        $eventData = array_merge($step1, $step2, $step3, $validated);
        $eventData['user_id'] = Auth::id();
        $eventData['slug'] = Str::slug($eventData['titre']);

        // Handle image upload
        if ($request->hasFile('image_couverture')) {
            $eventData['image_couverture'] = $request->file('image_couverture')->store('events', 'public');
        }

        // Create the event
        $event = \App\Models\Event::create($eventData);

        // Attach premium options if any
        $premiumOptions = [];
        if (!empty($eventData['premium_mise_en_avant'])) {
            $premiumOptions[] = 1; // mise_en_avant
        }
        if (!empty($eventData['premium_newsletter'])) {
            $premiumOptions[] = 2; // newsletter
        }
        if (!empty($eventData['premium_reseaux'])) {
            $premiumOptions[] = 3; // reseaux
        }
        if (!empty($premiumOptions)) {
            $event->premiumOptions()->sync($premiumOptions);
        }

        // Clear session
        session()->forget(['event_step1', 'event_step2', 'event_step3']);

        return redirect()->to('/events/' . $event->slug)
            ->with('success', 'Evenement cree avec succes!');
    }
}