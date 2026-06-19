<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $categories = Category::all();

        $events = [
            [
                'titre' => 'Festival de Musique Urbana 2026',
                'description' => 'Le plus grand festival de musique urbaine d\'Afrique de l\'Ouest.Venez découvrir les plus grands artistes de la scène africaine et internationale pour une soirée inoubliable.',
                'date' => '2026-06-15',
                'heure' => '20:00',
                'lieu' => 'Stade de Kégué, Lomé',
                'latitude' => 6.1375,
                'longitude' => 1.2123,
                'statut' => 'publie',
                'premium_mise_en_avant' => true,
                'category_id' => $categories->where('slug', 'festival')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Conférence Tech Innovation 2026',
                'description' => 'Une conférence exclusive sur les nouvelles technologies et l\'innovation. Les experts les plus renommés du secteur partagent leurs connaissances.',
                'date' => '2026-06-20',
                'heure' => '09:00',
                'lieu' => 'Hotel du 2 Février, Lomé',
                'latitude' => 6.1258,
                'longitude' => 1.2315,
                'statut' => 'publie',
                'premium_newsletter' => true,
                'category_id' => $categories->where('slug', 'formation')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Match de Football Togo vs Ghana',
                'description' => 'Le match tant attendu entre les Éperviers du Togo et les Black Stars du Ghana. Une rencontre décisive pour les qualifications.',
                'date' => '2026-07-01',
                'heure' => '16:00',
                'lieu' => 'Stade de Kégué, Lomé',
                'latitude' => 6.1375,
                'longitude' => 1.2123,
                'statut' => 'publie',
                'premium_mise_en_avant' => true,
                'category_id' => $categories->where('slug', 'sport')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Masterclass DJ avec David Guetta',
                'description' => 'Une masterclass exclusive animée par le célèbre DJ David Guetta. Apprenez les techniques professionnelles du mixage.',
                'date' => '2026-07-10',
                'heure' => '14:00',
                'lieu' => 'Palais des Congrès, Lomé',
                'latitude' => 6.1300,
                'longitude' => 1.2200,
                'statut' => 'publie',
                'category_id' => $categories->where('slug', 'masterclass')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Salon International des Arts',
                'description' => 'Découvrez les œuvres des plus grands artistes africains. Peintures, sculptures, œuvres numériques...',
                'date' => '2026-07-25',
                'heure' => '10:00',
                'lieu' => 'Centre Culturel Français, Lomé',
                'latitude' => 6.1280,
                'longitude' => 1.2150,
                'statut' => 'publie',
                'premium_reseaux' => true,
                'category_id' => $categories->where('slug', 'culture')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Concert Live Band - Soirée Jazz',
                'description' => 'Une soirée Jazz exceptionnel avec le groupe Africa Jazz Band. Détendez-vous dans une atmosphère relaxante.',
                'date' => '2026-08-05',
                'heure' => '19:00',
                'lieu' => 'Blitz Club, Lomé',
                'latitude' => 6.1400,
                'longitude' => 1.2100,
                'statut' => 'publie',
                'category_id' => $categories->where('slug', 'concert')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Formation Entrepreneurship au Féminin',
                'description' => 'Une formation complète pour les femmes thérapeutes qui souhaitent lancer leur propre entreprise. Mentorship et networking.',
                'date' => '2026-08-12',
                'heure' => '08:30',
                'lieu' => 'Maison de la Femme, Lomé',
                'latitude' => 6.1100,
                'longitude' => 1.2250,
                'statut' => 'publie',
                'category_id' => $categories->where('slug', 'formation')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Corporate Networking Event',
                'description' => 'Événement de networking pour les professionnels. Connectez-vous avec les décisionnaires et développe votre réseau.',
                'date' => '2026-08-20',
                'heure' => '18:00',
                'lieu' => 'Radisson Blu, Lomé',
                'latitude' => 6.1350,
                'longitude' => 1.2300,
                'statut' => 'publie',
                'category_id' => $categories->where('slug', 'corporate')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Marathon de Lomé 2026',
                'description' => 'Participez au marathon annuel de Lomé. Courses de 5km, 10km, 21km et 42km. Ouvert à tous les niveaux.',
                'date' => '2026-09-05',
                'heure' => '05:00',
                'lieu' => 'Place de l\'Indépendance, Lomé',
                'latitude' => 6.1250,
                'longitude' => 1.2200,
                'statut' => 'publie',
                'premium_mise_en_avant' => true,
                'category_id' => $categories->where('slug', 'sport')->first()->id,
                'user_id' => $admin->id,
            ],
            [
                'titre' => 'Festival des Saveurs - Street Food',
                'description' => 'Le plus grand festival de street food d\'Afrique de l\'Ouest. Découvrez les délices culinaires de toute la région.',
                'date' => '2026-09-15',
                'heure' => '11:00',
                'lieu' => 'Parc National du Togo, Lomé',
                'latitude' => 6.1500,
                'longitude' => 1.2050,
                'statut' => 'publie',
                'category_id' => $categories->where('slug', 'festival')->first()->id,
                'user_id' => $admin->id,
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create($eventData);

            // Create tickets for the event
            if ($eventData['statut'] === 'publie') {
                Ticket::create([
                    'event_id' => $event->id,
                    'nom' => 'Standard',
                    'prix' => rand(1000, 5000),
                    'quantite_totale' => rand(50, 200),
                    'quantite_vendue' => rand(10, 50),
                ]);

                Ticket::create([
                    'event_id' => $event->id,
                    'nom' => 'VIP',
                    'prix' => rand(5000, 15000),
                    'quantite_totale' => rand(20, 50),
                    'quantite_vendue' => rand(5, 20),
                ]);
            }
        }
    }
}