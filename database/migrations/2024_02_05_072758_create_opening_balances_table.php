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
        Schema::create('opening_balances', function (Blueprint $table) {
            $table->id('balance_id');
            $table->decimal('opening_balance');
            $table->decimal('remaining_balance');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('period_id');

            $table->foreign('account_id')->references('account_id')->on('accounts');
            $table->foreign('period_id')->references('period_id')->on('posting_periods');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_balances');
    }
};
