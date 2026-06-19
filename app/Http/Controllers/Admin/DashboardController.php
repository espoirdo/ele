<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Payment;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events' => Event::count(),
            'pending_events' => Event::where('statut', 'en_attente')->count(),
            'published_events' => Event::where('statut', 'publie')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_revenus' => Payment::where('statut', 'success')->sum('montant'),
            'revenus_this_month' => Payment::where('statut', 'success')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('montant'),
            'premium_actifs' => Event::where('premium_mise_en_avant', true)
                ->where('statut', 'publie')
                ->count(),
            'total_comments' => Comment::count(),
            'comments_pending' => Comment::where('approuve', false)->count(),
            'comments_signaled' => Comment::where('signale', true)->count(),
        ];

        $recentEvents = Event::with('user', 'category')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('user', 'event')
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentEvents', 'recentPayments', 'recentUsers'));
    }
}