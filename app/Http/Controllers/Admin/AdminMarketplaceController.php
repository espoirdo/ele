<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMarketplaceController extends Controller
{
    /**
     * Display Marketplace listings
     */
    public function index(Request $request)
    {
        $listings = MarketplaceListing::with('user')
            ->when($request->statut, function ($query, $statut) {
                return $query->where('statut', $statut);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        return view('admin.marketplace.index', compact('listings'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.marketplace.create', compact('users'));
    }

    /**
     * Store new listing
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'statut' => 'required|in:actif,inactif',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('marketplace', 'public');
        }

        MarketplaceListing::create($validated);

        return redirect()->route('admin.marketplace.index')->with('success', 'Listing créé avec succès');
    }

    /**
     * Show edit form
     */
    public function edit(MarketplaceListing $marketplace)
    {
        $users = User::orderBy('name')->get();
        return view('admin.marketplace.edit', compact('marketplace', 'users'));
    }

    /**
     * Update listing
     */
    public function update(Request $request, MarketplaceListing $marketplace)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'statut' => 'required|in:actif,inactif',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($marketplace->image) {
                Storage::disk('public')->delete($marketplace->image);
            }
            $validated['image'] = $request->file('image')->store('marketplace', 'public');
        }

        $marketplace->update($validated);

        return redirect()->route('admin.marketplace.index')->with('success', 'Listing mis à jour');
    }

    /**
     * Delete listing
     */
    public function destroy(MarketplaceListing $marketplace)
    {
        // Delete image
        if ($marketplace->image) {
            Storage::disk('public')->delete($marketplace->image);
        }

        $marketplace->delete();

        return redirect()->route('admin.marketplace.index')->with('success', 'Listing supprimé');
    }
}