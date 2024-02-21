<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_group_books', function (Blueprint $table) {
            $table->unsignedBigInteger('account_group_id');
            $table->unsignedBigInteger('book_id');
            $table->foreign('account_group_id')->references('account_group_id')->on('account_groups');
            $table->foreign('book_id')->references('book_id')->on('books');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_group_books');
    }
};
