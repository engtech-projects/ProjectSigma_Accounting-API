<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE `journal_entry` CHANGE COLUMN `posting_period_id` `fiscal_year_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `journal_entry` CHANGE COLUMN `period_id` `posting_period_id` BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `journal_entry` CHANGE COLUMN `posting_period_id` `period_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `journal_entry` CHANGE COLUMN `fiscal_year_id` `posting_period_id` BIGINT UNSIGNED NOT NULL');
    }
};
