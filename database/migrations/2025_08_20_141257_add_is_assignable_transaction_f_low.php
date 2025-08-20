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
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->boolean('is_assignable')->default(false)->after('priority');
        });
        Schema::table('transaction_flow_model', function (Blueprint $table) {
            $table->boolean('is_assignable')->default(false)->after('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->dropColumn('is_assignable');
        });
        Schema::table('transaction_flow_model', function (Blueprint $table) {
            $table->dropColumn('is_assignable');
        });
    }
};
