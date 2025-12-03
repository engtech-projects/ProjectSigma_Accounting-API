<?php

use App\Enums\PaymentRequestType;
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
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('payment_request', function (Blueprint $table) {
            $table->enum('type', allowed: [
                PaymentRequestType::PRF->value,
                PaymentRequestType::PAYROLL->value,
                PaymentRequestType::PO->value,
                PaymentRequestType::LIQUIDATION->value,
            ])->default(PaymentRequestType::PRF->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
