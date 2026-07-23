<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get existing columns for vip_payments
        $vipColumns = DB::getSchemaBuilder()->getColumnListing('vip_payments');

        // Add missing columns to vip_payments using raw SQL only if they don't exist
        if (!in_array('pzgate_reference', $vipColumns)) {
            DB::statement('ALTER TABLE vip_payments ADD pzgate_reference VARCHAR(255) NULL');
        }
        if (!in_array('pzgate_transaction_id', $vipColumns)) {
            DB::statement('ALTER TABLE vip_payments ADD pzgate_transaction_id VARCHAR(255) NULL');
        }
        if (!in_array('pzgate_status', $vipColumns)) {
            DB::statement('ALTER TABLE vip_payments ADD pzgate_status VARCHAR(255) NULL');
        }
        if (!in_array('pzgate_response', $vipColumns)) {
            DB::statement('ALTER TABLE vip_payments ADD pzgate_response JSON NULL');
        }

        // Get existing columns for premium_payments
        $premiumColumns = DB::getSchemaBuilder()->getColumnListing('premium_payments');

        // Add missing columns to premium_payments using raw SQL only if they don't exist
        if (!in_array('transaction_id', $premiumColumns)) {
            DB::statement('ALTER TABLE premium_payments ADD transaction_id VARCHAR(255) NULL');
        }
        if (!in_array('pzgate_reference', $premiumColumns)) {
            DB::statement('ALTER TABLE premium_payments ADD pzgate_reference VARCHAR(255) NULL');
        }
        if (!in_array('pzgate_transaction_id', $premiumColumns)) {
            DB::statement('ALTER TABLE premium_payments ADD pzgate_transaction_id VARCHAR(255) NULL');
        }
        if (!in_array('pzgate_status', $premiumColumns)) {
            DB::statement('ALTER TABLE premium_payments ADD pzgate_status VARCHAR(255) NULL');
        }
        if (!in_array('pzgate_response', $premiumColumns)) {
            DB::statement('ALTER TABLE premium_payments ADD pzgate_response JSON NULL');
        }
    }

    public function down(): void
    {
        // No rollback
    }
};
