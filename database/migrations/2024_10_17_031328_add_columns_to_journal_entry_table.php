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
        Schema::table('journal_entry', function (Blueprint $table) {
			$table->foreignId('posting_period_id')->constrained('posting_periods');
			$table->foreignId('period_id')->constrained('periods');
			$table->text('remarks')->nullable();
			$table->string('reference_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
			$table->dropColumn('remarks');
			$table->dropColumn('posting_period_id');
			$table->dropColumn('period_id');
        });
    }
};
