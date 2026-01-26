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
        Schema::create('payment_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stakeholder_id')->constrained('stakeholder');
            $table->string('prf_no')->unique();
            $table->string('request_date');
            $table->string('description')->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_request');
    }
};
