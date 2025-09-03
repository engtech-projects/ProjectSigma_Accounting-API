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
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->string('unique_name')->nullable()->after('payment_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->dropColumn('unique_name');
        });
    }
};
