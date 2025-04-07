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
        DB::statement('ALTER TABLE `projects` MODIFY `created_at` TIMESTAMP NULL AFTER `manager`');
        DB::statement('ALTER TABLE `projects` MODIFY `updated_at` TIMESTAMP NULL AFTER `created_at`');
        DB::statement('ALTER TABLE `projects` MODIFY `deleted_at` TIMESTAMP NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
