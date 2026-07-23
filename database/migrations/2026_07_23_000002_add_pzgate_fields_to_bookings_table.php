<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('pzgate_transaction_id')->nullable()->after('status');
            $table->string('pzgate_reference')->nullable()->after('pzgate_transaction_id');
            $table->string('pzgate_status')->nullable()->after('pzgate_reference');
            $table->json('pzgate_response')->nullable()->after('pzgate_status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'pzgate_transaction_id',
                'pzgate_reference',
                'pzgate_status',
                'pzgate_response',
            ]);
        });
    }
};
