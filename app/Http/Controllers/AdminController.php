<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $usersCount = User::count();
        $eventsCount = Event::count();

        return view('admin.dashboard', compact('usersCount', 'eventsCount'));
    }

    public function users()
    {
        $this->authorizeAdmin();

        $users = User::latest()->get();

        return view('admin.users', compact('users'));
    }

    public function destroyUser(User $user): RedirectResponse
    {
        $this->authorizeAdmin();

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Impossible de supprimer votre propre compte.');
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function events()
    {
        $this->authorizeAdmin();

        $events = Event::with(['user', 'category'])->latest()->get();

        return view('admin.events', compact('events'));
    }

    public function publishEvent(Event $event): RedirectResponse
    {
        $this->authorizeAdmin();

        $event->update(['statut' => 'publie']);

        return back()->with('success', 'Événement publié.');
    }

    public function destroyEvent(Event $event): RedirectResponse
    {
        $this->authorizeAdmin();

        $event->delete();

        return back()->with('success', 'Événement supprimé.');
    }

    private function authorizeAdmin(): void
    {
        if (! auth()->check() || ! auth()->user()->is_admin) {
            abort(403);
        }
    }
}
