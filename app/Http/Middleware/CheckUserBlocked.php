<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserBlocked
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->is_blocked) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte a été suspendu.');
        }

        return $next($request);
    }
}
