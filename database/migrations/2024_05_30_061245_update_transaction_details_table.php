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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->unsignedBigInteger('stakeholder_group_id')->after('transaction_id');
            $table->foreign('stakeholder_group_id')->references('stakeholder_group_id')->on('stakeholder_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('stakeholder_group_id');
            $table->dropColumn('stakeholder_group_id');
        });
    }
};
