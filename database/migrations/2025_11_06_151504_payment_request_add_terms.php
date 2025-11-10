<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            // Delivery terms: PICK_UP, DELIVER_ON_SITE, FOR_SHIPMENT
            $table->enum('delivery_terms', [
                    'PICK_UP',
                    'DELIVER_ON_SITE',
                    'FOR_SHIPMENT',
                ])->after('with_holding_tax_id')
                ->nullable();

            // Payment terms: various payment/credit options
            $table->enum('payment_terms', [
                    'PRE_PAYMENT_IN_FULL',
                    'CREDIT_7_DAYS',
                    'CREDIT_15_DAYS',
                    'CREDIT_30_DAYS',
                    'PROGRESS_BILLING',
                ])->after('delivery_terms')
                ->nullable();

            // Availability: AVAILABLE, UNAVAILABLE, ORDER_BASIS variants
            $table->enum('availability', [
                    'AVAILABLE',
                    'UNAVAILABLE',
                    'ORDER_BASIS_7_DAYS',
                    'ORDER_BASIS_15_DAYS',
                    'ORDER_BASIS_30_DAYS',
                ])->after('payment_terms')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_request', function (Blueprint $table) {
            $table->dropColumn('delivery_terms');
            $table->dropColumn('payment_terms');
            $table->dropColumn('availability');
        });
    }
};
