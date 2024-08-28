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
                $table->dropColumn('stakeholder_group_id');
            }
            $table->unsignedBigInteger('stakeholder_id')->nullable()->after('transaction_id')->change();
            $table->foreign('stakeholder_id')->references('stakeholder_id')->on('stakeholders');
            $table->unsignedBigInteger('account_id')->nullable()->after('stakeholder_id')->change();
            $table->foreign('account_id')->references('account_id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_details', 'stakeholder_id')) {
                $table->dropConstrainedForeignId('stakeholder_id');
                $table->dropColumn('stakeholder_id');
            }
            if (Schema::hasColumn('transaction_details', 'account_id')) {
                $table->dropConstrainedForeignId('account_id');
                $table->dropColumn('account_id');
            }
        });
    }
};
