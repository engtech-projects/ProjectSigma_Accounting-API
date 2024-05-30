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
            if (Schema::hasColumn('transaction_details', 'stakeholder_group_id')) {
                $table->dropConstrainedForeignId('stakeholder_group_id');
            }
            $table->unsignedBigInteger('stakeholder_id')->after('transaction_id');
            $table->foreign('stakeholder_id')->references('stakeholder_id')->on('stakeholders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropConstrainedForeignId('stakeholder_id');
            $table->dropColumn('stakeholder_id');
        });
    }
};
