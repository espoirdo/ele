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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'paygate_tx_reference')) {
                $table->string('paygate_tx_reference')->nullable()->after('statut');
            }
            if (!Schema::hasColumn('bookings', 'paygate_identifier')) {
                $table->string('paygate_identifier')->nullable()->after('paygate_tx_reference');
            }
            if (!Schema::hasColumn('bookings', 'paygate_status')) {
                $table->integer('paygate_status')->nullable()->after('paygate_identifier');
            }
            if (!Schema::hasColumn('bookings', 'paygate_response')) {
                $table->json('paygate_response')->nullable()->after('paygate_status');
            }
            if (!Schema::hasColumn('bookings', 'paygate_payment_reference')) {
                $table->string('paygate_payment_reference')->nullable()->after('paygate_response');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'paygate_tx_reference',
                'paygate_identifier',
                'paygate_status',
                'paygate_response',
                'paygate_payment_reference',
            ]);
        });
    }
};
