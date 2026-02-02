<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropUnique('payment_request_prf_no_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->unique('prf_no', 'payment_request_prf_no_unique');
        });
    }
};
