<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->enum('status', ['unposted', 'posted', 'void', 'drafted', 'open'])->default('open')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
