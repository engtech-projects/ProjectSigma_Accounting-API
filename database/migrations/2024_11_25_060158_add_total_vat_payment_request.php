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
            $table->decimal('total_vat_amount', 10, 2)->nullable()->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropColumn('total_vat_amount');
        });
    }
};
