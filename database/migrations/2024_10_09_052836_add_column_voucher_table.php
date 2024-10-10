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
        Schema::table('voucher', function (Blueprint $table) 
		{
			if (!Schema::hasColumn('voucher', 'voucher_type')) {
				$table->enum('voucher_type', ['disbursement', 'cash']);
            }
			if (!Schema::hasColumn('voucher', 'check_no')) {
				$table->string('check_no')->nullable();
            }

			if (!Schema::hasColumn('voucher', 'account_id')) {
				$table->unsignedBigInteger('account_id');
				$table->foreign('account_id')->references('account_id')->on('accounts');
            }

			
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    	$table->dropColumn('voucher_type');
		$table->dropColumn('check_no');
		$table->dropColumn('account_id');
    }
};
