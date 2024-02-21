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
        Schema::create('stakeholder_type_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('stakeholder_type_id');
            $table->unsignedBigInteger('stakeholder_group_id');
            $table->foreign('stakeholder_type_id')->references('stakeholder_type_id')->on('stakeholder_types');
            $table->foreign('stakeholder_group_id')->references('stakeholder_group_id')->on('stakeholder_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakeholder_type_groups');
    }
};
