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
        // Change columns order in terms table
        Schema::table('terms', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('location')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default columns order in terms table
        Schema::table('terms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->after('id')->change();
            $table->text('description')->nullable()->after('name')->change();
            $table->bigInteger('account_id')->after('description')->change();
            $table->string('type')->after('account_id')->change();
            $table->string('debit_credit')->after('location')->change();
            $table->string('location')->after('type')->change();
            $table->timestamp('created_at')->nullable()->after('location')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
