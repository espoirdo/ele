<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Mail\EventApprovedMail;
use App\Mail\EventRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with('user', 'category')
            ->when($request->statut, function ($query, $statut) {
                return $query->where('statut', $statut);
            })
            ->when($request->categorie, function ($query, $categorie) {
                return $query->where('category_id', $categorie);
            })
            ->when($request->search, function ($query, $search) {
                return $query->where('titre', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        $categories = \App\Models\Category::all();

        return view('admin.events.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        $event->load('user', 'category', 'tickets', 'comments.user');
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $categories = \App\Models\Category::all();
        $event->load('tickets');
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'date' => 'required|date',
            'heure' => 'required',
            'lieu' => 'required|string|max:255',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'est_gratuit' => 'boolean',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Événement mis à jour');
    }

    public function destroy(Event $event)
    {
        if ($event->image_couverture) {
            Storage::delete($event->image_couverture);
        }
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Événement supprimé');
    }

    public function approve(Event $event)
    {
        $event->update(['statut' => 'publie']);

        if ($event->user) {
            Mail::to($event->user->email)->send(new EventApprovedMail($event));
        }

        return back()->with('success', 'Événement approuvé et publié');
    }

    public function reject(Request $request, Event $event)
    {
        $validated = $request->validate([
            'raison_rejet' => 'required|string|min:10',
        ]);

        $event->update([
            'statut' => 'rejete',
            'raison_rejet' => $validated['raison_rejet'],
        ]);

        if ($event->user) {
            Mail::to($event->user->email)->send(new EventRejectedMail($event));
        }

        return back()->with('success', 'Événement rejeté');
    }

    public function updatePlaces(Request $request, Event $event)
    {
        $validated = $request->validate([
            'nb_places' => 'required|integer|min:0',
        ]);

        $event->update(['nb_places' => $validated['nb_places']]);

        return back()->with('success', 'Nombre de places mis a jour');
    }
}