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
        // Add type_billet column if it doesn't exist
        if (!Schema::hasColumn('bookings', 'type_billet')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->enum('type_billet', ['classique', 'vip', 'vvip'])->nullable()->after('total');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('type_billet');
        });
    }
};