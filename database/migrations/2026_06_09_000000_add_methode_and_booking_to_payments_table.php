<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('methode', ['tmoney', 'flooz', 'carte'])->nullable()->after('statut');
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete()->after('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
            $table->dropColumn(['booking_id', 'methode']);
        });
    }
};