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
        DB::statement('ALTER TABLE terms MODIFY created_at TIMESTAMP NULL AFTER location');
        DB::statement('ALTER TABLE terms MODIFY updated_at TIMESTAMP NULL AFTER created_at');
        DB::statement('ALTER TABLE terms MODIFY deleted_at TIMESTAMP NULL AFTER updated_at');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
