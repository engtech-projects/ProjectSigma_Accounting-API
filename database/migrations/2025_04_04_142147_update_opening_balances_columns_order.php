<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        DB::statement('ALTER TABLE `opening_balances` MODIFY `created_at` TIMESTAMP NULL AFTER `posting_period_id`');
        DB::statement('ALTER TABLE `opening_balances` MODIFY `deleted_at` TIMESTAMP NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
