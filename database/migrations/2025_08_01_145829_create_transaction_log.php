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
        Schema::create('transaction_log', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['request', 'checkList', 'voucher', 'journal', 'approval', 'payment', 'attachment', 'budget'])->default('request');
            $table->string('transaction_code');
            $table->string('description');
            $table->string('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_log');
    }
};
