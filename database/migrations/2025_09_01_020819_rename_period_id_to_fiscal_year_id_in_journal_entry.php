<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->dropForeign('journal_entry_posting_period_id_foreign');
            $table->dropForeign('journal_entry_period_id_foreign');
            $table->renameColumn('posting_period_id', 'fiscal_year_id');
            $table->renameColumn('period_id', 'posting_period_id');
        });
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year')->onDelete('cascade');
            $table->foreign('posting_period_id')->references('id')->on('posting_periods')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->dropForeign(['fiscal_year_id']);
            $table->dropForeign(['posting_period_id']);
        });
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->renameColumn('fiscal_year_id', 'posting_period_id');
            $table->renameColumn('posting_period_id', 'period_id');
        });
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->foreign('posting_period_id', 'journal_entry_posting_period_id_foreign')
                ->references('id')->on('posting_periods')->onDelete('cascade');
            $table->foreign('period_id', 'journal_entry_period_id_foreign')
                ->references('id')->on('periods')->onDelete('cascade');
        });
    }
};
