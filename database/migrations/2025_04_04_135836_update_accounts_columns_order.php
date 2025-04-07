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
        //Change the order of columns
        DB::statement('ALTER TABLE `accounts` MODIFY `created_at` VARCHAR(255) NOT NULL AFTER `sub_group_id`');
        DB::statement('ALTER TABLE `accounts` MODIFY `updated_at` TIMESTAMP NULL AFTER `created_at`');
        DB::statement('ALTER TABLE `accounts` MODIFY `deleted_at` TIMESTAMP NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
