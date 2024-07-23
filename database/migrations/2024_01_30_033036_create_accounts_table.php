<?php

use App\Enums\AccountStatus;
use App\Enums\BankReconciliation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id('account_id');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('account_description')->nullable();
            $table->enum('bank_reconciliation', ['yes', 'no'])
                ->index()
                ->default(BankReconciliation::YES);

            $table->enum('status', ['active', 'inactive'])
                ->index()
                ->default(AccountStatus::ACTIVE);

            $table->string('statement')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')
                ->references('type_id')
                ->on('account_types');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
