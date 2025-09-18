<?php

use App\Enums\TransactionFlowStatus;
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
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->enum('status', [
                TransactionFlowStatus::PENDING->value,
                TransactionFlowStatus::IN_PROGRESS->value,
                TransactionFlowStatus::DONE->value,
                TransactionFlowStatus::REJECTED->value,
                TransactionFlowStatus::SKIPPED->value,
            ])->default(TransactionFlowStatus::PENDING->value)->after('priority')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_flow', function (Blueprint $table) {
            $table->enum('status', [
                TransactionFlowStatus::PENDING->value,
                TransactionFlowStatus::IN_PROGRESS->value,
                TransactionFlowStatus::DONE->value,
                TransactionFlowStatus::REJECTED->value,
            ])->default(TransactionFlowStatus::PENDING->value)->after('priority')->change();
        });
    }
};
