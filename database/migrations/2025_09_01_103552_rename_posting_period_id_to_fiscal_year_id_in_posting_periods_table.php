<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::useNativeSchemaOperationsIfPossible();
        Schema::table('posting_periods', function (Blueprint $table) {
            $table->renameColumn('posting_period_id', 'fiscal_year_id');
        });
        Schema::useNativeSchemaOperationsIfPossible(false);
    }

    public function down(): void
    {
        Schema::table('posting_periods', function (Blueprint $table) {
            $table->renameColumn('fiscal_year_id', 'posting_period_id');
        });
    }
};
