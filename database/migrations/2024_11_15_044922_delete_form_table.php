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
        Schema::dropIfExists('form');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('form', function (Blueprint $table) {
            $table->unsignedBigInteger('stakeholder_id');
			$table->unsignedBigInteger('formable_id')->nullable();
			$table->string('formable_type')->nullable();
			$table->enum('status', ['pending', 'approved', 'rejected', 'issued'])->default('pending');
            $table->timestamps();
        });
    }
};
