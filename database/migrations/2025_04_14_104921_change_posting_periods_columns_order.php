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
        // Change columns order in posting_periods table
        Schema::table('posting_periods', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('status')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default columns order in posting_periods table
        Schema::table('posting_periods', function (Blueprint $table) {
            $table->id();
            $table->date('period_start')->after('id')->change();
            $table->date('period_end')->after('period_start')->change();
            $table->enum('status', ['open', 'closed'])->after('period_end')->change();
            $table->timestamp('created_at')->nullable()->after('status')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
