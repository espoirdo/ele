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
