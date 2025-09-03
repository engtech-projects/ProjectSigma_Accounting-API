<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->renameColumn('posting_period_id', 'fiscal_year_id');
            $table->renameColumn('period_id', 'posting_period_id');
        });
    }

    public function down(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->renameColumn('fiscal_year_id', 'posting_period_id');
            $table->renameColumn('posting_period_id', 'period_id');
        });
    }
};
