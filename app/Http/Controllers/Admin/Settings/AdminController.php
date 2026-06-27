<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Affiche la page de gestion des administrateurs
     */
    public function index()
    {
        // Recuperer tous les utilisateurs avec role admin
        $admins = User::where('role', 'admin')
            ->with('roleModel')
            ->orderBy('created_at', 'desc')
            ->get();

        $roles = Role::all();

        return view('admin.settings.admins.index', compact('admins', 'roles'));
    }

    /**
     * Cree un nouvel administrateur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Generer un mot de passe temporaire
        $tempPassword = Str::random(10);

        // Creer l'utilisateur avec le role admin
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'role' => 'admin',
            'role_id' => $validated['role_id'],
        ]);

        // TODO: Envoyer un email avec le mot de passe temporaire
        // Mail::to($user->email)->send(new AdminCreated($user, $tempPassword));

        return back()->with('success', 'Administrateur cree avec succes. Mot de passe temporaire: ' . $tempPassword);
    }

    /**
     * Active/desactive un administrateur
     */
    public function toggle(Request $request, User $user)
    {
        // Verifier qu'on ne peut pas se desactiver soi-meme
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas desactiver votre propre compte');
        }

        $user->update([
            'is_blocked' => !$user->is_blocked
        ]);

        $status = $user->is_blocked ? 'desactive' : 'active';
        return back()->with('success', 'Compte ' . $status . ' avec succes');
    }

    /**
     * Supprime un administrateur
     */
    public function destroy(Request $request, User $user)
    {
        // Verifier qu'on ne peut pas se supprimer soi-meme
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }

        $request->validate([
            'confirm_delete' => 'required|accepted'
        ]);

        $user->delete();

        return back()->with('success', 'Administrateur supprime avec succes');
    }
}