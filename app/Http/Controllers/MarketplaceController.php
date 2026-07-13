<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    /**
     * Display Marketplace listings
     */
    public function index(Request $request)
    {
        $listings = MarketplaceListing::with('user')
            ->actif()
            ->latest()
            ->paginate(12);

        $isVip = Auth::check() && Auth::user()->isVip();
        $isAuthenticated = Auth::check();

        return view('marketplace.index', compact('listings', 'isVip', 'isAuthenticated'));
    }

    /**
     * Show create listing form (VIP only)
     */
    public function create(Request $request)
    {
        $isVip = Auth::check() && Auth::user()->isVip();

        if (!$isVip) {
            return redirect()->route('vip.subscribe.show')->with('error', 'La publication sur la Marketplace est réservée aux membres VIP.');
        }

        return view('marketplace.create');
    }

    /**
     * Store new listing (VIP only)
     */
    public function store(Request $request)
    {
        $isVip = Auth::check() && Auth::user()->isVip();

        if (!$isVip) {
            return redirect()->route('vip.subscribe.show')->with('error', 'La publication sur la Marketplace est réservée aux membres VIP.');
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $listing = new MarketplaceListing();
        $listing->user_id = Auth::id();
        $listing->titre = $validated['titre'];
        $listing->description = $validated['description'];
        $listing->prix = $validated['prix'] ?? null;
        $listing->statut = 'actif';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('marketplace', 'public');
            $listing->image = $path;
        }

        $listing->save();

        return redirect()->route('marketplace.index')->with('success', 'Votre produit a été publié sur la Marketplace!');
    }
}