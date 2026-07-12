<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireVip
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isVip()) {
            return redirect()
                ->route('vip.subscribe.show')
                ->with('warning', 'Accès réservé aux membres VIP. Souscrivez pour accéder à la Marketplace.');
        }

        return $next($request);
    }
}