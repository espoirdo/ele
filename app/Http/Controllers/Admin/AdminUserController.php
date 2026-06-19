<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->blocked, function ($query) {
                return $query->where('is_blocked', true);
            })
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('events', 'comments', 'payments');
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Impossible de supprimer un administrateur');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé');
    }

    public function block(User $user)
    {
        $user->update(['is_blocked' => true]);
        return back()->with('success', 'Utilisateur bloqué');
    }

    public function unblock(User $user)
    {
        $user->update(['is_blocked' => false]);
        return back()->with('success', 'Utilisateur débloqué');
    }

    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);
        return back()->with('success', 'Utilisateur promu administrateur');
    }

    public function demote(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Impossible de se démettre soi-même');
        }
        $user->update(['role' => 'user']);
        return back()->with('success', 'Rôle rétrogradé');
    }

    public function verifyEmail(User $user)
    {
        $user->update(['email_verified_at' => now()]);
        return back()->with('success', 'Email vérifié manuellement');
    }
}