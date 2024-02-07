<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->string('transaction_no');
            $table->date('transaction_date');
            $table->enum('status', ['unposted', 'posted'])->default('unposted');
            $table->string('reference_no');
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('period_id');

            $table->foreign('transaction_type_id')->references('transaction_type_id')->on('transaction_types');
            $table->foreign('period_id')->references('period_id')->on('posting_periods');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
