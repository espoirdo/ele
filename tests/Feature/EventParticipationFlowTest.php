<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventParticipationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_event_shows_participate_cta_instead_of_payment_cta(): void
    {
        $user = User::factory()->create();

        $event = Event::create([
            'user_id' => $user->id,
            'category_id' => 1,
            'titre' => 'Festival gratuit test',
            'description' => 'Description',
            'date' => now()->addDay(),
            'heure' => '20:00',
            'lieu' => 'Lomé',
            'latitude' => 6.1375,
            'longitude' => 1.2123,
            'statut' => 'publie',
            'est_gratuit' => true,
            'billet_classique_actif' => true,
            'billet_classique_prix' => 0,
            'billet_vip_actif' => false,
            'billet_vip_prix' => 0,
            'billet_vvip_actif' => false,
            'billet_vvip_prix' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('events.show', $event))
            ->assertOk()
            ->assertSee('/evenements/' . $event->slug . '/participer')
            ->assertDontSee('/paiement/' . $event->slug);
    }

    public function test_free_ticket_payment_route_redirects_to_booking_confirmation(): void
    {
        $user = User::factory()->create();

        $event = Event::create([
            'user_id' => $user->id,
            'category_id' => 1,
            'titre' => 'Festival gratuit test 2',
            'description' => 'Description',
            'date' => now()->addDay(),
            'heure' => '20:00',
            'lieu' => 'Lomé',
            'latitude' => 6.1375,
            'longitude' => 1.2123,
            'statut' => 'publie',
            'est_gratuit' => true,
            'billet_classique_actif' => true,
            'billet_classique_prix' => 0,
            'billet_vip_actif' => false,
            'billet_vip_prix' => 0,
            'billet_vvip_actif' => false,
            'billet_vvip_prix' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('payment.show', ['event' => $event]) . '?type_billet=classique')
            ->assertRedirect(route('booking.confirm.show', $event->slug));
    }
}
