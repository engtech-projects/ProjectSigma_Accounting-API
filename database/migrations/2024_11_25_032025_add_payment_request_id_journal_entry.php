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
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->foreignId('payment_request_id')->nullable()->constrained('payment_request')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->dropForeign(['payment_request_id']);
            $table->dropColumn('payment_request_id');
        });
    }
};
