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
        Schema::create('approval_routes', function (Blueprint $table) {
            $table->id('route_id');
            $table->integer('sequence');
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('approver_role_id');
            $table->string('state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_routes');
    }
};
