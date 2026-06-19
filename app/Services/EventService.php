<?php

namespace App\Services;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class EventService
{
    public function createFromRequest(StoreEventRequest $request): Event
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['statut'] = $request->input('statut', 'brouillon');
        $data['is_featured'] = $request->boolean('is_featured');

        // Default nb_places if not provided
        if (!isset($data['nb_places'])) {
            $data['nb_places'] = 100;
        }

        if ($request->hasFile('image_couverture')) {
            $data['image_couverture'] = $request->file('image_couverture')->store('events', 'public');
        }

        $event = Event::create($data);

        // Create tickets only if provided
        if (! empty($data['tickets']) && is_array($data['tickets'])) {
            foreach ($data['tickets'] as $ticketPayload) {
                $event->tickets()->create([
                    'nom' => $ticketPayload['nom'],
                    'prix' => $ticketPayload['prix'],
                    'quantite_totale' => $ticketPayload['quantite'],
                    'quantite_vendue' => 0,
                ]);
            }
        }

        if (! empty($data['premium_options'])) {
            $event->premiumOptions()->sync($data['premium_options']);
        }

        return $event;
    }
}
