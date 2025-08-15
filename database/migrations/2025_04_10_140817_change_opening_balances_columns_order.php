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
        //Change columns order in opening_balances table
        Schema::table('opening_balances', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('period_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Restore default columns order in opening_balances table
        Schema::table('opening_balances', function (Blueprint $table) {
            $table->id();
            $table->decimal('opening_balance', 10, 2)->after('id')->change();
            $table->decimal('remaining_balance', 10, 2)->after('opening_balance')->change();
            $table->bigInteger('account_id')->after('remaining_balance')->change();
            $table->bigInteger('posting_period_id')->after('account_id')->change();
            $table->timestamp('created_at')->nullable()->after('posting_period_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
