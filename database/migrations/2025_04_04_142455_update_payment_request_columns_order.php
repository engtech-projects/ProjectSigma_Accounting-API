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
        DB::statement('ALTER TABLE `payment_request` MODIFY `created_at` TIMESTAMP NULL AFTER `with_holding_tax_id`');
        DB::statement('ALTER TABLE `payment_request` MODIFY `updated_at` TIMESTAMP NULL AFTER `created_at`');
        DB::statement('ALTER TABLE `payment_request`  MODIFY `deleted_at` TIMESTAMP NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
