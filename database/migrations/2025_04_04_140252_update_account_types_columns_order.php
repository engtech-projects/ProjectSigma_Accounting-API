<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Change order of column 'deleted_at' after updated_at
        DB::statement('ALTER TABLE `account_types` MODIFY `deleted_at` TIMESTAMP NULL AFTER `updated_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
