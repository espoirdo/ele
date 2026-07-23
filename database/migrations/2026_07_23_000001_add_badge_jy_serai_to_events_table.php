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
            // Badge "J'y serai" columns
            $table->string('affiche_officielle')->nullable()->after('image_couverture');
            $table->enum('badge_zone_type', ['cercle', 'rectangle'])->default('cercle')->nullable()->after('affiche_officielle');
            $table->integer('badge_zone_x')->nullable()->after('badge_zone_type');
            $table->integer('badge_zone_y')->nullable()->after('badge_zone_x');
            $table->integer('badge_zone_width')->nullable()->after('badge_zone_y');
            $table->integer('badge_zone_height')->nullable()->after('badge_zone_width');
            $table->boolean('badge_actif')->default(false)->after('badge_zone_height');
            $table->boolean('badge_valide_admin')->default(false)->after('badge_actif');
            $table->integer('badge_nb_generations')->default(0)->after('badge_valide_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'affiche_officielle',
                'badge_zone_type',
                'badge_zone_x',
                'badge_zone_y',
                'badge_zone_width',
                'badge_zone_height',
                'badge_actif',
                'badge_valide_admin',
                'badge_nb_generations',
            ]);
        });
    }
};
