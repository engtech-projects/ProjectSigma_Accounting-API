<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('periods') && ! Schema::hasTable('posting_periods')) {
            Schema::rename('periods', 'posting_periods');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('posting_periods') && ! Schema::hasTable('periods')) {
            Schema::rename('posting_periods', 'periods');
        }
    }
};
