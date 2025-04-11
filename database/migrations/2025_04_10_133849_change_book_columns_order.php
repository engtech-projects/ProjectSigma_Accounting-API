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
        //Change columns order in book table
        Schema::table('book', function (Blueprint $table) {
            $table->string('created_at')->after('account_group_id')->change();
            $table->string('updated_at')->nullable()->after('created_at')->change();
            $table->string('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Restore default columns order in book table
        Schema::table('book', function (Blueprint $table) {
            $table->id();
            $table->string('name')->after('id')->change();
            $table->string('code')->after('name')->change();
            $table->bigInteger('account_group_id')->after('code')->change();
            $table->string('created_at')->after('account_group_id')->change();
            $table->string('updated_at')->nullable()->after('created_at')->change();
            $table->string('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
