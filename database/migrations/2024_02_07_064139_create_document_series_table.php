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
        Schema::create('document_series', function (Blueprint $table) {
            $table->id('series_id');
            $table->string('series_scheme');
            $table->string('series_description');
            $table->integer('next_number');
            $table->enum('status', ['active', 'inactive']);
            $table->unsignedBigInteger('transaction_type_id');
            $table->foreign('transaction_type_id')->references('transaction_type_id')->on('transaction_types');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_series');
    }
};
