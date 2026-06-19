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
            return redirect()->route('events.create.step1');
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

        // Base validation
        $rules = [
            'est_gratuit' => 'required|boolean',
            'nombre_places' => 'required|integer|min:1',
        ];

        // Add price validation only if not free
        $isFree = filter_var($request->input('est_gratuit'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true;
        if (!$isFree) {
            $rules['prix'] = 'required|numeric|min:0';
            $rules['tickets'] = 'nullable|array|max:5';
            $rules['tickets.*.nom'] = 'required|string|max:100';
            $rules['tickets.*.prix'] = 'required|numeric|min:0';
            $rules['tickets.*.quantite'] = 'required|integer|min:1';
        } else {
            $rules['prix'] = 'nullable|numeric|min:0';
        }

        $validated = $request->validate($rules);

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

        // Map nombre_places to nb_places for the events table
        if (isset($eventData['nombre_places'])) {
            $eventData['nb_places'] = $eventData['nombre_places'];
        }

        // Handle image upload
        if ($request->hasFile('image_couverture')) {
            $eventData['image_couverture'] = $request->file('image_couverture')->store('events', 'public');
        }

        // Create the event
        $event = \App\Models\Event::create($eventData);

        // Determine if event is free (handle both string and boolean)
        $isFree = filter_var($eventData['est_gratuit'], FILTER_VALIDATE_BOOLEAN);

        // Create tickets if not free event
        if (!$isFree && !empty($eventData['tickets'])) {
            foreach ($eventData['tickets'] as $ticketPayload) {
                $event->tickets()->create([
                    'nom' => $ticketPayload['nom'],
                    'prix' => $ticketPayload['prix'],
                    'quantite_totale' => $ticketPayload['quantite'],
                    'quantite_vendue' => 0,
                ]);
            }
        } elseif ($isFree) {
            // Create a free ticket
            $event->tickets()->create([
                'nom' => 'Entrée gratuite',
                'prix' => 0,
                'quantite_totale' => $eventData['nombre_places'],
                'quantite_vendue' => 0,
            ]);
        }

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