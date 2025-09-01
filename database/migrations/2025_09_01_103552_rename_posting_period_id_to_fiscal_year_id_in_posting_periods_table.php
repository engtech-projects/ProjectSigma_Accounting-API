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
        Schema::table('posting_periods', function (Blueprint $table) {
            // Rename the column
            $table->renameColumn('fiscal_year_id', 'fiscal_year_id');
        });
        
        // Add foreign key constraint after renaming
        Schema::table('posting_periods', function (Blueprint $table) {
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year')->onDelete('cascade');
            
            // Add index for better performance
            $table->index(['fiscal_year_id', 'start_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posting_periods', function (Blueprint $table) {
            // Drop constraints first
            $table->dropForeign(['fiscal_year_id']);
            $table->dropIndex(['fiscal_year_id', 'start_date']);
        });
        
        Schema::table('posting_periods', function (Blueprint $table) {
            // Rename back
            $table->renameColumn('fiscal_year_id', 'posting_period_id');
        });
    }
};