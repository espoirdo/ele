<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\PremiumOption;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'user'])
            ->where('statut', 'publie');

        if ($request->has('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        if ($request->has('categorie')) {
            $query->whereHas(
                'category',
                fn ($q) => $q->where('slug', $request->categorie)
            );
        }

        if ($request->has('gratuit')) {
            $query->where('est_gratuit', $request->gratuit === '1');
        }

        $events = $query->latest()->paginate(12);
        $topEvents = Event::query()
            ->where('premium_mise_en_avant', true)
            ->where('statut', 'publie')
            ->with(['category', 'user'])
            ->latest()
            ->take(3)
            ->get();
        $categories = Category::all();

        return view('events.index', compact('events', 'topEvents', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $premiumOptions = PremiumOption::all();

        return view('events.create', compact('categories', 'premiumOptions'));
    }

    public function store(StoreEventRequest $request, EventService $eventService)
    {
        $event = $eventService->createFromRequest($request);

        return redirect()->to('/events/' . $event->slug)
            ->with('success', 'L’événement a bien été créé.');
    }

    public function show(string $slug)
    {
        $event = Event::with(['category', 'user', 'tickets', 'comments.user'])
            ->where('slug', $slug)
            ->where('statut', 'publie')
            ->firstOrFail();

        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id && ! Auth::user()->is_admin) {
            abort(403);
        }

        $categories = Category::all();
        return view('events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id && ! Auth::user()->is_admin) {
            abort(403);
        }

        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'lieu' => ['required', 'string', 'max:255'],
            'date_heure' => ['required', 'date'],
            'heure' => ['nullable', 'date_format:H:i'],
            'prix' => ['nullable', 'numeric', 'min:0'],
            'image_couverture' => ['nullable', 'image', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
            'statut' => ['nullable', 'in:brouillon,publie'],
        ]);

        $data['date'] = $request->input('date_heure');
        if ($request->filled('heure')) {
            $data['heure'] = $request->input('heure');
        }

        if ($request->hasFile('image_couverture')) {
            $data['image_couverture'] = $request->file('image_couverture')->store('events', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $event->update($data);

        return redirect()->to('/events/' . $event->slug)
            ->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id && ! Auth::user()->is_admin) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Événement supprimé.');
    }

    public function draft(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'category' => 'nullable',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'heure' => 'nullable',
            'lieu' => 'nullable|string|max:255',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'options' => 'nullable|array',
        ]);

        $event = Event::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category'] ?? null,
            'titre' => $validated['titre'],
            'description' => $validated['description'] ?? '',
            'date' => $validated['date'] ?? now()->addDays(7),
            'heure' => $validated['heure'] ?? '20:00',
            'lieu' => $validated['lieu'] ?? '',
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'statut' => 'brouillon',
            'est_gratuit' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Brouillon enregistré',
            'event_id' => $event->id,
        ]);
    }
}
