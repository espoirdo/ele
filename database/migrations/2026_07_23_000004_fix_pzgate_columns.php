<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add missing columns to vip_payments if they don't exist
        Schema::table('vip_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('vip_payments', 'pzgate_reference')) {
                $table->string('pzgate_reference')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('vip_payments', 'pzgate_transaction_id')) {
                $table->string('pzgate_transaction_id')->nullable()->after('pzgate_reference');
            }
            if (!Schema::hasColumn('vip_payments', 'pzgate_status')) {
                $table->string('pzgate_status')->nullable()->after('pzgate_transaction_id');
            }
            if (!Schema::hasColumn('vip_payments', 'pzgate_response')) {
                $table->json('pzgate_response')->nullable()->after('pzgate_status');
            }
        });

        // Add missing columns to premium_payments if they don't exist
        Schema::table('premium_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('premium_payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable()->after('statut');
            }
            if (!Schema::hasColumn('premium_payments', 'pzgate_reference')) {
                $table->string('pzgate_reference')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('premium_payments', 'pzgate_transaction_id')) {
                $table->string('pzgate_transaction_id')->nullable()->after('pzgate_reference');
            }
            if (!Schema::hasColumn('premium_payments', 'pzgate_status')) {
                $table->string('pzgate_status')->nullable()->after('pzgate_transaction_id');
            }
            if (!Schema::hasColumn('premium_payments', 'pzgate_response')) {
                $table->json('pzgate_response')->nullable()->after('pzgate_status');
            }
        });
    }

    public function down(): void
    {
        // No rollback needed - this migration only adds missing columns
    }
};
