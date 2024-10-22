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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
			$table->foreignId('stakeholder_id')->constrained('stakeholder')->nullable();			
			$table->unsignedBigInteger('formable_id');
			$table->string('formable_type');
			$table->enum('status', ['pending', 'approved', 'rejected', 'issued'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
