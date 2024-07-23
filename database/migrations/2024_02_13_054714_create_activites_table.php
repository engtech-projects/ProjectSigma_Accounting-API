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
        Schema::create('activites', function (Blueprint $table) {
            $table->id('activity_id');
            $table->unsignedBigInteger('act_type_id');
            $table->string('action');
            $table->json('model');
            $table->string('action_by');
            $table->dateTime('activity_date');
            $table->foreign('act_type_id')->references('act_type_id')->on('activity_types');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
