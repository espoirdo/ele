<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('statut')->default('publie');
            $table->boolean('premium_mise_en_avant')->default(false);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->string('lieu')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->nullable();
            $table->decimal('prix', 10, 2)->default(0);
            $table->integer('capacite')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};