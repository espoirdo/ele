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
        // Remove nb_places from events table if it exists
        if (Schema::hasColumn('events', 'nb_places')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('nb_places');
            });
        }

        // Remove nb_places from bookings table if it exists
        if (Schema::hasColumn('bookings', 'nb_places')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('nb_places');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back nb_places to events table
        Schema::table('events', function (Blueprint $table) {
            $table->integer('nb_places')->default(100)->after('prix');
        });

        // Add back nb_places to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->integer('nb_places')->default(1)->after('total');
        });
    }
};