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
        Schema::table('transaction_flow_model', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->after('category');
            $table->string('user_name')->nullable()->after('user_id');
        });
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->after('category');
            $table->string('user_name')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_flow_model', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('user_name');
        });
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('user_name');
        });
    }
};
