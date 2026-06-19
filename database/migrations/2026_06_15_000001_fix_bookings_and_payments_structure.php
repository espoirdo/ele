<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'ticket_id')) {
                $table->foreignId('ticket_id')->nullable()->after('event_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('bookings', 'numero_reservation')) {
                $table->string('numero_reservation')->unique()->nullable()->after('status');
            }
            if (!Schema::hasColumn('bookings', 'ticket_path')) {
                $table->string('ticket_path')->nullable()->after('numero_reservation')->comment('Chemin du fichier PDF du ticket');
            }
        });

        // Add missing columns to payments table
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'booking_id')) {
                $table->foreignId('booking_id')->nullable()->after('event_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('payments', 'methode')) {
                $table->enum('methode', ['tmoney', 'flooz', 'carte', 'autre'])->nullable()->after('statut');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['ticket_id']);
            $table->dropColumn(['ticket_id', 'numero_reservation', 'ticket_path']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['booking_id']);
            $table->dropColumn(['booking_id', 'methode']);
        });
    }
};
