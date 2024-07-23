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
        Schema::create('account_has_group', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('account_id')->on('accounts');

            $table->unsignedBigInteger('account_group_id');
            $table->foreign('account_group_id')->references('account_group_id')->on('account_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_has_groups');
    }
};
