<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('created_at')->after('sub_group_id')->change();
            $table->string('updated_at')->nullable()->after('created_at')->change();
            $table->string('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_type_id')->after('id')->change();
            $table->string('account_number')->after('account_type_id')->change();
            $table->string('account_name')->after('account_number')->change();
            $table->string('account_description')->nullable()->after('account_name')->change();
            $table->enum('bank_reconciliation', ['yes', 'no'])->index()->default('yes')->after('account_description')->change();
            $table->tinyInteger('is_active')->index()->default(true)->after('bank_reconciliation')->change();
            $table->string('statement')->nullable()->after('is_active')->change();
            $table->bigInteger('report_group_id')->nullable()->after('statement')->change();
            $table->bigInteger('sub_group_id')->nullable()->after('report_group_id')->change();
            $table->string('created_at')->after('sub_group_id')->change();
            $table->string('updated_at')->nullable()->after('created_at')->change();
            $table->string('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
