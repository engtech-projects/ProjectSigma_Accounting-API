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
        DB::statement('ALTER TABLE `payment_request_details` MODIFY `created_at` TIMESTAMP NULL AFTER `particular_group_id`');
        DB::statement('ALTER TABLE `payment_request_details` MODIFY `updated_at` TIMESTAMP NULL AFTER `created_at`');
        DB::statement('ALTER TABLE `payment_request_details` MODIFY `deleted_at` TIMESTAMP NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
