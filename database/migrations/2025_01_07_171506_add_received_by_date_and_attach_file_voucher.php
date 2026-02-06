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
        Schema::table('voucher', function (Blueprint $table) {
            $table->integer('received_by')->nullable()->before('deleted_at');
            $table->date('received_date')->nullable()->before('deleted_at');
            $table->string('attach_file')->nullable()->before('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher', function (Blueprint $table) {
            $table->dropColumn(['received_by', 'received_date', 'attach_file']);
        });
    }
};
