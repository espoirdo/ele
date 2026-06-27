<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * Verifie si l'utilisateur a la permission requise pour acceder a la route
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        // Si pas connecte, rediriger vers login
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Si l'utilisateur est un admin avec le role admin_total, il a toutes les permissions
        if ($user->role === 'admin' && $user->roleModel && $user->roleModel->slug === 'admin_total') {
            return $next($request);
        }

        // Verifier la permission via le role
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        // Pas de permission - retourner une erreur ou rediriger
        return redirect()->route('admin.dashboard')->with('error', 'Vous n\'avez pas la permission d\'acceder a cette section.');
    }
}