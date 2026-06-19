<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $top3 = Event::publie()->premium()->latest()->take(3)->get();
        $featuredEvent = $top3->first();
        $categories = Category::ordered()->get();
        $events = Event::publie()->with('category')
            ->when($request->query('categorie'), fn($q,$c)=>$q->where('category_id',$c))
            ->latest()->take(6)->get();
        $recentEvents = $events;

        $totalEvents = Event::publie()->count();
        $totalCategories = Category::count();
        $totalUsers = User::count();

        return view('home.index', compact('top3', 'featuredEvent', 'categories', 'events', 'recentEvents', 'totalEvents', 'totalCategories', 'totalUsers'));
    }
}