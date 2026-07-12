<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVipExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->is_vip && $user->vip_expires_at) {
            if ($user->vip_expires_at->isPast()) {
                $user->update([
                    'is_vip' => false,
                ]);
            }
        }

        return $next($request);
    }
}