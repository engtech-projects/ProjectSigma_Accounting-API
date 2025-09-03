<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stakeholder', function (Blueprint $table) {
            $table->enum('type', ['supplier', 'employee', 'projects'])->nullable()->after('name');
            $table->unsignedBigInteger('source_id')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stakeholder', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
