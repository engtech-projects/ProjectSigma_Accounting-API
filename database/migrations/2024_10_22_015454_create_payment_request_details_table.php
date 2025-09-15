<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_request_id')->constrained('payment_request');
            $table->foreignId('stakeholder_id')->constrained('stakeholder');
            $table->tinyText('particulars')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('vat', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_request_details');
    }
};
