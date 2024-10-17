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
			$table->string('check_no')->nullable();
			$table->string('voucher_no')->unique();
            $table->foreignId('stakeholder_id')->constrained('stakeholder')->nullable();
			$table->text('particulars');
			$table->double('net_amount');
			$table->text('amount_in_words')->nullable();
			$table->enum('status', ['draft', 'pending', 'aproved', 'rejected', 'void'])->default('draft');
			$table->date('voucher_date');
			$table->date('date_encoded');
			$table->foreignId('account_id')->constrained('accounts');
			$table->foreignId('book_id')->constrained('book');
			$table->softDeletes();
            $table->timestamps();
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
