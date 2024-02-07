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
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id('transaction_type_id');
            $table->string('transaction_type_name');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('account_id');
            $table->foreign('book_id')->references('book_id')->on('books');
            $table->foreign('account_id')->references('account_id')->on('accounts');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }
};
