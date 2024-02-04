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
        Schema::create('account_types', function (Blueprint $table) {
            $table->id('type_id');
            $table->string('type_number');
            $table->string('type_name');
            $table->boolean('has_opening_balance')
                ->default(false);
            $table->unsignedBigInteger('category_id');

            $table->foreign('category_id')
                ->references('category_id')
                ->on('account_categories');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_types');
    }
};
