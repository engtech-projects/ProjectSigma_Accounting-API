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
        // Change columns order in sub_groups table
        Schema::table('sub_groups', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('description')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default columns order in sub_groups table
        Schema::table('sub_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->after('id')->change();
            $table->string('description')->nullable()->after('name')->change();
            $table->timestamp('created_at')->nullable()->after('description')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
