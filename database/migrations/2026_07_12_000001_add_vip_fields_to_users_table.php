<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_vip')) {
                $table->boolean('is_vip')->default(false)->after('role_id');
            }
            if (!Schema::hasColumn('users', 'vip_expires_at')) {
                $table->timestamp('vip_expires_at')->nullable()->after('is_vip');
            }
            if (!Schema::hasColumn('users', 'vip_subscribed_at')) {
                $table->timestamp('vip_subscribed_at')->nullable()->after('vip_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_vip', 'vip_expires_at', 'vip_subscribed_at']);
        });
    }
};