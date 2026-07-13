<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_newsletter_index_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        NewsletterSubscriber::create(['email' => 'test@example.com']);

        $this->actingAs($admin)
            ->get(route('admin.newsletter.index'))
            ->assertStatus(200)
            ->assertSee('Envoyer une newsletter');
    }
}
