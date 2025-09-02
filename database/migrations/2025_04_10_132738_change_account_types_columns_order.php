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
        // Change columns order in account_types table
        Schema::table('account_types', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('notation')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default columns order in account_types table
        Schema::table('account_types', function (Blueprint $table) {
            $table->id();
            $table->string('account_type')->after('id')->change();
            $table->enum('account_category', ['asset', 'equity', 'expenses', 'income', 'liabilities', 'revenue', 'capital'])->after('account_type')->change();
            $table->enum('balance_type', ['debit', 'credit'])->after('account_category')->change();
            $table->enum('notation', ['+', '-'])->after('balance_type')->change();
            $table->timestamp('created_at')->nullable()->after('notation')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
