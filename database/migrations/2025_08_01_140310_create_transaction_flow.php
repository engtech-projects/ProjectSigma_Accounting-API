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
        Schema::create('transaction_flow', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_request_id')->constrained('payment_request')->onDelete('cascade');
            $table->string('name');
            $table->string('description');
            $table->enum('status', ['done', 'pending'])->default('pending');
            $table->string('priority');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_flow');
    }
};
