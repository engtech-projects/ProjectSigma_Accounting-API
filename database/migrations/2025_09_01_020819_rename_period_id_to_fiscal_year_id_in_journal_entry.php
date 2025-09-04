<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        Schema::useNativeSchemaOperationsIfPossible();
        if (Schema::hasTable('journal_entry')) {
            if (Schema::hasColumn('journal_entry', 'posting_period_id') && !Schema::hasColumn('journal_entry', 'fiscal_year_id')) {
                DB::statement('ALTER TABLE `journal_entry` CHANGE `posting_period_id` `fiscal_year_id` BIGINT UNSIGNED NOT NULL');
            }
            if (Schema::hasColumn('journal_entry', 'period_id') && !Schema::hasColumn('journal_entry', 'posting_period_id')) {
                DB::statement('ALTER TABLE `journal_entry` CHANGE `period_id` `posting_period_id` BIGINT UNSIGNED NOT NULL');
            }
        }
        Schema::useNativeSchemaOperationsIfPossible(false);
    }

    public function down(): void
    {
        Schema::useNativeSchemaOperationsIfPossible();
        if (Schema::hasTable('journal_entry')) {
            if (Schema::hasColumn('journal_entry', 'posting_period_id') && !Schema::hasColumn('journal_entry', 'period_id')) {
                DB::statement('ALTER TABLE `journal_entry` CHANGE `posting_period_id` `period_id` BIGINT UNSIGNED NOT NULL');
            }
            if (Schema::hasColumn('journal_entry', 'fiscal_year_id') && !Schema::hasColumn('journal_entry', 'posting_period_id')) {
                DB::statement('ALTER TABLE `journal_entry` CHANGE `fiscal_year_id` `posting_period_id` BIGINT UNSIGNED NOT NULL');
            }
        }
        Schema::useNativeSchemaOperationsIfPossible(false);
    }
};
