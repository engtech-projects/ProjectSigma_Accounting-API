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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger("stakeholder_id")->after('period_id');
            $table->string("description")->after('stakeholder_id');
            $table->text("note")->after('description');
            $table->double("amount")->after('note');

            $table->foreign("stakeholder_id")->references("stakeholder_id")->on("stakeholders");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign("stakeholder_id");
            $table->dropColumn('stakeholder_id');
            $table->dropColumn('description');
            $table->dropColumn('note');
            $table->dropColumn('amount');
        });
    }
};
