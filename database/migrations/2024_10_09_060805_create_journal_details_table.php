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
        Schema::create('journal_details', function (Blueprint $table) {
            $table->id();
			$table->foreignId('journal_id')->constrained('journal_entry')->onDelete('cascade');
			$table->unsignedBigInteger('account_id');
			$table->foreign('account_id')->references('account_id')->on('accounts');
			$table->string('payee');
			$table->tinyText('description')->nullable();
			$table->decimal('debit', 10, 2)->default(0.00);
			$table->decimal('credit', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_details');
    }
};
