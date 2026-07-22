<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si la table existe avant de tracker
        try {
            if (!DB::getSchemaBuilder()->hasTable('page_visits')) {
                return $next($request);
            }
        } catch (\Exception $e) {
            // Si erreur de connexion ou autre, passer
            return $next($request);
        }

        $sessionId = session()->getId();
        $today = now()->toDateString();

        $alreadyTracked = cache()->remember(
            "visit_tracked_{$sessionId}_{$today}",
            now()->endOfDay(),
            function () use ($sessionId, $today) {
                return DB::table('page_visits')
                    ->where('session_id', $sessionId)
                    ->whereDate('visited_at', $today)
                    ->exists();
            }
        );

        if (!$alreadyTracked) {
            DB::table('page_visits')->insert([
                'session_id' => $sessionId,
                'user_id'    => auth()->id(),
                'visited_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            cache()->forget("visit_tracked_{$sessionId}_{$today}");
        }

        return $next($request);
    }
}
