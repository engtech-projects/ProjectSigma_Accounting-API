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
        Schema::create('stakeholder_group_books', function (Blueprint $table) {
            $table->unsignedBigInteger('stakeholder_group_id');
            $table->unsignedBigInteger('book_id');
            $table->foreign('stakeholder_group_id')->references('stakeholder_group_id')->on('stakeholder_groups');
            $table->foreign('book_id')->references('book_id')->on('books');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakeholder_group_books');
    }
};
