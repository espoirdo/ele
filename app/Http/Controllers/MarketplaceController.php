<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceListing;
use Illuminate\Http\Request;

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

        return view('marketplace.index', compact('listings'));
    }
}