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
        DB::statement('ALTER TABLE stakeholder MODIFY created_at TIMESTAMP NULL AFTER `stakeholdable_id`');
        DB::statement('ALTER TABLE stakeholder MODIFY updated_at TIMESTAMP NULL AFTER `created_at`');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
