<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            if (!Schema::hasColumn('newsletter_subscribers', 'name')) {
                $table->string('name')->nullable()->after('email');
            }

            if (!Schema::hasColumn('newsletter_subscribers', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('newsletter_subscribers', function (Blueprint $table) {
            if (Schema::hasColumn('newsletter_subscribers', 'name')) {
                $table->dropColumn('name');
            }

            if (Schema::hasColumn('newsletter_subscribers', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
