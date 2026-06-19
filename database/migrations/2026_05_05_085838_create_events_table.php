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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('lieu');
            $table->date('date');
            $table->time('heure')->nullable();
            $table->decimal('prix', 10, 2)->default(0);
            $table->string('image_couverture')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('statut', ['publie', 'brouillon'])->default('brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
