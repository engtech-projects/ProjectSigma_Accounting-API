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
        // Change columns order in projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('manager')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default columns order in projects table
        Schema::table('projects', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('source_id')->nullable()->after('id')->change();
            $table->string('name')->after('source_id')->change();
            $table->string('manager')->nullable()->after('description')->change();
            $table->timestamp('created_at')->nullable()->after('manager')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
