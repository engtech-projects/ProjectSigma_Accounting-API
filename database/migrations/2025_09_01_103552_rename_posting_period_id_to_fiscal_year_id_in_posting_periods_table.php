<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE posting_periods RENAME COLUMN posting_period_id TO fiscal_year_id');
    }
    public function down(): void
    {
        DB::statement('ALTER TABLE posting_periods RENAME COLUMN fiscal_year_id TO posting_period_id');
    }
};