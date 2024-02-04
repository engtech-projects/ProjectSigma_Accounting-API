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
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id('detail_id');
            $table->string('detail_account_no');
            $table->string('detail_title');
            $table->decimal('debit')->default(0);
            $table->decimal('credit')->default(0);
            $table->text('detail_description')->nullable();
            $table->string('ref_no')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_details');
    }
};
