<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premium_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('options'); // array of selected options
            $table->decimal('total', 10, 0);
            $table->string('moyen_paiement')->nullable(); // tmoney, flooz, carte
            $table->enum('statut', ['en_attente', 'confirme', 'echoue'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('premium_payments');
    }
};
