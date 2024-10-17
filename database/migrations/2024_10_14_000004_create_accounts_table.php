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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
			$table->foreignId('account_type_id')->constrained('account_types');
			$table->string('account_number');
            $table->string('account_name');
            $table->string('account_description')->nullable();
            $table->enum('bank_reconciliation', ['yes', 'no'])->index()->default('yes');
			$table->boolean('is_active')->index()->default(true);
			$table->string('statement')->nullable();
			$table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
