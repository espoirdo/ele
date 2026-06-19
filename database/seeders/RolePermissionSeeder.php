<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            ['nom' => 'Voir le dashboard', 'slug' => 'dashboard.view'],
            ['nom' => 'Gérer les événements', 'slug' => 'evenements.manage'],
            ['nom' => 'Gérer les utilisateurs', 'slug' => 'utilisateurs.manage'],
            ['nom' => 'Gérer les catégories', 'slug' => 'categories.manage'],
            ['nom' => 'Gérer les paiements', 'slug' => 'paiements.manage'],
            ['nom' => 'Gérer les réservations', 'slug' => 'reservations.manage'],
            ['nom' => 'Gérer les commentaires', 'slug' => 'commentaires.manage'],
            ['nom' => 'Gérer les paramètres', 'slug' => 'parametres.manage'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['slug' => $perm['slug']], $perm);
        }

        // Créer les rôles
        $adminTotal = Role::updateOrCreate(['slug' => 'admin_total'], ['nom' => 'Administrateur total']);
        $gestionnaire = Role::updateOrCreate(['slug' => 'gestionnaire'], ['nom' => 'Gestionnaire d\'événements']);
        $moderateur = Role::updateOrCreate(['slug' => 'moderateur'], ['nom' => 'Modérateur']);

        // Administrateur total : toutes les permissions
        $adminTotal->permissions()->sync(Permission::all()->pluck('id'));

        // Gestionnaire d'événements : événements, catégories, réservations
        $gestionnaire->permissions()->sync([
            Permission::where('slug', 'dashboard.view')->first()->id,
            Permission::where('slug', 'evenements.manage')->first()->id,
            Permission::where('slug', 'categories.manage')->first()->id,
            Permission::where('slug', 'reservations.manage')->first()->id,
        ]);

        // Modérateur : utilisateurs et commentaires
        $moderateur->permissions()->sync([
            Permission::where('slug', 'dashboard.view')->first()->id,
            Permission::where('slug', 'utilisateurs.manage')->first()->id,
            Permission::where('slug', 'commentaires.manage')->first()->id,
        ]);
    }
}