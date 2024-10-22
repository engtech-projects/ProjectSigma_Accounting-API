<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('voucher', function (Blueprint $table) {
			$table->string('reference_no')->nullable()->unique();
			$table->unsignedBigInteger('formable_id')->nullable();
			$table->string('formable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('voucher', function (Blueprint $table) {
			$table->dropColumn('reference_no');
			$table->dropColumn('formable_id')->nullable();
			$table->dropColumn('formable_type')->nullable();
        });
    }
};
