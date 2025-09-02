<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posting_periods', function (Blueprint $table) {
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year')->onDelete('cascade');
            $table->unique(['fiscal_year_id', 'start_date'], 'posting_periods_fy_start_unique');
        });
    }

    public function down(): void
    {
        Schema::table('posting_periods', function (Blueprint $table) {
            $table->dropForeign(['fiscal_year_id']);
            $table->dropUnique('posting_periods_fy_start_unique');
        });
    }
};
