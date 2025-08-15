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
        Schema::table('payment_request', function (Blueprint $table) {
            $table->integer('created_by')->nullable();
            $table->string('request_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('request_status');
        });
    }
};
