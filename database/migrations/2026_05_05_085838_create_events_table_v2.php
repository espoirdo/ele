<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'premium_mise_en_avant')) {
                    $table->boolean('premium_mise_en_avant')->default(false)->after('statut');
                }
                if (!Schema::hasColumn('events', 'premium_newsletter')) {
                    $table->boolean('premium_newsletter')->default(false)->after('premium_mise_en_avant');
                }
                if (!Schema::hasColumn('events', 'premium_reseaux')) {
                    $table->boolean('premium_reseaux')->default(false)->after('premium_newsletter');
                }
                if (!Schema::hasColumn('events', 'est_gratuit')) {
                    $table->boolean('est_gratuit')->default(true)->after('premium_reseaux');
                }
                if (!Schema::hasColumn('events', 'raison_rejet')) {
                    $table->text('raison_rejet')->nullable()->after('est_gratuit');
                }
                if (!Schema::hasColumn('events', 'latitude')) {
                    $table->decimal('latitude', 10, 7)->nullable()->after('lieu');
                }
                if (!Schema::hasColumn('events', 'longitude')) {
                    $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumnIfExists(['premium_mise_en_avant', 'premium_newsletter', 'premium_reseaux', 'est_gratuit', 'raison_rejet', 'latitude', 'longitude']);
        });
    }
};