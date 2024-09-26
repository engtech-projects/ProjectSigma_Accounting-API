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
        Schema::create('voucher_line_items', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('voucher_id');
			$table->foreign('voucher_id')->references('id')->on('voucher');
			$table->unsignedBigInteger('account_id')->index();
			$table->string('contact')->nullable();
			$table->double('debit')->nullable();
			$table->double('credit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_line_items');
    }
};
