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
        Schema::create('voucher', function (Blueprint $table) {
            $table->id();
			$table->string('voucher_no')->unique();
            $table->string('payee');
			$table->text('particulars');
			$table->double('net_amount');
			$table->double('amount_in_words')->nullable();
			$table->string('status')->default('draft');
			$table->date('voucher_date');
			$table->unsignedBigInteger('created_by');
			$table->softDeletes();
            $table->timestamps();
			$table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};
