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
        // Add ticket type columns if they don't exist
        if (!Schema::hasColumn('events', 'billet_classique_actif')) {
            Schema::table('events', function (Blueprint $table) {
                $table->boolean('billet_classique_actif')->default(false)->after('est_gratuit');
                $table->decimal('billet_classique_prix', 10, 2)->nullable()->after('billet_classique_actif');
                $table->boolean('billet_vip_actif')->default(false)->after('billet_classique_prix');
                $table->decimal('billet_vip_prix', 10, 2)->nullable()->after('billet_vip_actif');
                $table->boolean('billet_vvip_actif')->default(false)->after('billet_vip_prix');
                $table->decimal('billet_vvip_prix', 10, 2)->nullable()->after('billet_vvip_actif');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'billet_classique_actif',
                'billet_classique_prix',
                'billet_vip_actif',
                'billet_vip_prix',
                'billet_vvip_actif',
                'billet_vvip_prix',
            ]);
        });
    }
};