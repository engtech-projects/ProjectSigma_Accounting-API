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
        Schema::create('account_balance', function (Blueprint $table) {
            $table->unsignedBigInteger('opening_balance_id');
            $table->unsignedBigInteger('account_id');
            $table->decimal('total_balance')->default(0);


            $table->foreign('opening_balance_id')->references('opening_balance_id')->on('opening_balances');
            $table->foreign('account_id')
                ->references('account_id')
                ->on('accounts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_balance');
    }
};
