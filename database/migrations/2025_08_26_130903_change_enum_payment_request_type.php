<?php

use App\Enums\PaymentRequestType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('payment_request', function (Blueprint $table) {
            $table->enum('type', [
                PaymentRequestType::PRF->value,
                PaymentRequestType::PAYROLL->value,
                PaymentRequestType::PO->value,
            ])->default(PaymentRequestType::PRF->value);
        });
    }

    public function down(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('payment_request', function (Blueprint $table) {
            $table->enum('type', PaymentRequestType::toArray())
                ->default(PaymentRequestType::PRF->value);
        });
    }
};
