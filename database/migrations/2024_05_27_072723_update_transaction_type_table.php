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
        Schema::table('transaction_types', function (Blueprint $table) {
            if (Schema::hasColumn('transaction_types', 'account_id')) {
                $table->dropConstrainedForeignId('account_id');
            }
            $table->unsignedBigInteger('stakeholder_group_id')->nullable()->after('book_id');
            $table->foreign('stakeholder_group_id')->references('stakeholder_group_id')->on('stakeholder_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_types', function (Blueprint $table) {
            $table->dropConstrainedForeignId('stakeholder_group_id');
        });
    }
};
