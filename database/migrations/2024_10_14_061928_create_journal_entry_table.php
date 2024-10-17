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
        Schema::create('journal_entry', function (Blueprint $table) {
            $table->id();
			$table->string('journal_no');
			$table->date('journal_date');
			$table->foreignId('voucher_id')->constrained('voucher')->nullable();
			$table->enum('status', ['draft', 'void', 'open', 'posted'])->default('draft');
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
