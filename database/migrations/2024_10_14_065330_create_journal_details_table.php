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
			$table->foreignId('journal_id')->constrained('journal_entry');
			$table->foreignId('account_id')->constrained('accounts');
			$table->foreignId('stakeholder_id')->constrained('stakeholder')->nullable();
			$table->tinyText('description')->nullable();
			$table->decimal('debit', 10, 2)->nullable();
			$table->decimal('credit', 10, 2)->nullable();
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
