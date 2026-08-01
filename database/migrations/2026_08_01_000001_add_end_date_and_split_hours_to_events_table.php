<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Renommer `heure` (heure de début) en `heure_debut` si nécessaire
            if (Schema::hasColumn('events', 'heure') && !Schema::hasColumn('events', 'heure_debut')) {
                $table->renameColumn('heure', 'heure_debut');
            }

            // Ajouter la colonne `heure_fin` (nullable pour les événements existants)
            if (!Schema::hasColumn('events', 'heure_fin')) {
                $table->time('heure_fin')->nullable()->after('heure_debut');
            }

            // Ajouter la colonne `date_fin` (nullable pour les événements existants)
            if (!Schema::hasColumn('events', 'date_fin')) {
                $table->date('date_fin')->nullable()->after('date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'date_fin')) {
                $table->dropColumn('date_fin');
            }
            if (Schema::hasColumn('events', 'heure_fin')) {
                $table->dropColumn('heure_fin');
            }
            if (Schema::hasColumn('events', 'heure_debut') && Schema::hasColumn('events', 'heure')) {
                $table->renameColumn('heure_debut', 'heure');
            }
        });
    }
};
