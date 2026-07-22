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
        // Statistiques temps réel - avec fallback si tables n'existent pas
        $connectesMaintenant = 0;
        $visiteursAnonymes = 0;
        $connectesCeMois = 0;
        $visiteursTotal = 0;
        $visiteursCeMois = 0;
        $visiteursAujourdhui = 0;
        $nonVerifies = 0;

        try {
            // Sessions connectées
            $connectesMaintenant = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
                ->distinct('user_id')
                ->count('user_id');

            $visiteursAnonymes = DB::table('sessions')
                ->whereNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
                ->count();

            // Utilisateurs connectés ce mois
            $connectesCeMois = User::whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->startOfMonth())
                ->count();

            // Visiteurs si la table existe
            if (DB::getSchemaBuilder()->hasTable('page_visits')) {
                $visiteursTotal = DB::table('page_visits')->count();
                $visiteursCeMois = DB::table('page_visits')
                    ->where('visited_at', '>=', now()->startOfMonth())
                    ->count();
                $visiteursAujourdhui = DB::table('page_visits')
                    ->whereDate('visited_at', today())
                    ->count();
            }

            // Non vérifiés
            $nonVerifies = User::whereNull('email_verified_at')
                ->where('created_at', '<=', now()->subHours(24))
                ->count();
        } catch (\Exception $e) {
            // En cas d'erreur, garder les valeurs par défaut (0)
        }

        $realtimeStats = [
            'connectes_maintenant' => $connectesMaintenant,
            'visiteurs_anonymes' => $visiteursAnonymes,
            'connectes_ce_mois' => $connectesCeMois,
            'visiteurs_total' => $visiteursTotal,
            'visiteurs_ce_mois' => $visiteursCeMois,
            'visiteurs_aujourdhui' => $visiteursAujourdhui,
            'non_verifies' => $nonVerifies,
        ];

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

        return view('admin.dashboard.index', compact('stats', 'recentEvents', 'recentPayments', 'recentUsers', 'realtimeStats'));
    }

    /**
     * Return live stats as JSON for auto-refresh
     */
    public function liveStats()
    {
        $connectesMaintenant = 0;
        $connectesCeMois = 0;
        $visiteursTotal = 0;
        $nonVerifies = 0;

        try {
            $connectesMaintenant = DB::table('sessions')
                ->whereNotNull('user_id')
                ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
                ->distinct('user_id')
                ->count('user_id');

            $connectesCeMois = User::whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->startOfMonth())
                ->count();

            if (DB::getSchemaBuilder()->hasTable('page_visits')) {
                $visiteursTotal = DB::table('page_visits')->count();
            }

            $nonVerifies = User::whereNull('email_verified_at')
                ->where('created_at', '<=', now()->subHours(24))
                ->count();
        } catch (\Exception $e) {
            // Keep default values (0)
        }

        return response()->json([
            'connectes_actuellement' => $connectesMaintenant,
            'connectes_ce_mois' => $connectesCeMois,
            'visiteurs_total' => $visiteursTotal,
            'non_verifies' => $nonVerifies,
        ]);
    }

    /**
     * Send verification email reminder to unverified users
     */
    public function sendVerificationReminder()
    {
        $users = User::whereNull('email_verified_at')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        $count = 0;
        foreach ($users as $user) {
            try {
                $user->sendEmailVerificationNotification();
                $count++;
            } catch (\Exception $e) {
                // Continue even if one fails
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', "Rappel de vérification envoyé à {$count} utilisateur(s).");
    }
}