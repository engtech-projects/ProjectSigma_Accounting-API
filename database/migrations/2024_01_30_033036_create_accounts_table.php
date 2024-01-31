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
            $table->id();
            $table->string('account_number');
            $table->string('account_name');
            $table->string('account_description');
            $table->unsignedBigInteger('parent_account')->nullable();
            $table->enum('status', ['active', 'inactive'])
                ->index()
                ->default(AccountStatus::ACTIVE);
            $table->enum('bank_reconciliation', ['yes', 'no']);
            $table->string('statement')->nullable();
            $table->unsignedBigInteger('account_type_id');
            $table->foreign('account_type_id')
                ->references('id')
                ->on('account_types');
            $table->foreign('parent_account')->references('id')->on('accounts');
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
