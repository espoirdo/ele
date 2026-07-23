<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vip_payments', function (Blueprint $table) {
            $table->string('pzgate_reference')->nullable()->after('transaction_id');
            $table->string('pzgate_transaction_id')->nullable()->after('pzgate_reference');
            $table->string('pzgate_status')->nullable()->after('pzgate_transaction_id');
            $table->json('pzgate_response')->nullable()->after('pzgate_status');
        });

        Schema::table('premium_payments', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('statut');
            $table->string('pzgate_reference')->nullable()->after('transaction_id');
            $table->string('pzgate_transaction_id')->nullable()->after('pzgate_reference');
            $table->string('pzgate_status')->nullable()->after('pzgate_transaction_id');
            $table->json('pzgate_response')->nullable()->after('pzgate_status');
        });
    }

    public function down(): void
    {
        Schema::table('vip_payments', function (Blueprint $table) {
            $table->dropColumn([
                'pzgate_reference',
                'pzgate_transaction_id',
                'pzgate_status',
                'pzgate_response',
            ]);
        });

        Schema::table('premium_payments', function (Blueprint $table) {
            $table->dropColumn([
                'pzgate_reference',
                'pzgate_transaction_id',
                'pzgate_status',
                'pzgate_response',
            ]);
        });
    }
};
