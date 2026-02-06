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
        Schema::table('payment_request_details', function (Blueprint $table) {
            $table->foreignId('stakeholder_id')->nullable()->constrained('stakeholder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_request_details', function (Blueprint $table) {
            $table->dropForeign(['stakeholder_id']);
            $table->dropColumn('stakeholder_id');
        });
    }
};
