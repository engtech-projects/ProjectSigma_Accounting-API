<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //Change column order
        DB::statement('ALTER TABLE sub_groups MODIFY deleted_at TIMESTAMP NULL AFTER updated_at');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
