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
        // Change columns order in payment_request table
        Schema::table('payment_request', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('with_holding_tax_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default columns order in payment_request table
        Schema::table('payment_request', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stakeholder_id')->after('id')->change();
            $table->string('prf_no')->after('stakeholder_id')->change();
            $table->string('request_date')->after('prf_no')->change();
            $table->text('description')->after('request_date')->change();
            $table->decimal('total', 10, 2)->after('description')->change();
            $table->decimal('total_vat_amount', 10, 2)->after('total')->change();
            $table->json('approvals')->nullable()->after('total_vat_amount')->change();
            $table->int('created_by')->after('approvals')->change();
            $table->string('request_status')->after('created_by')->change();
            $table->string('type')->after('request_status')->change();
            $table->text('attachment_url')->after('type')->change();
            $table->bigInteger('with_holding_tax_id')->after('attachment_url')->change();
            $table->timestamp('created_at')->after('with_holding_tax_id')->change();
            $table->timestamp('updated_at')->after('created_at')->change();
            $table->timestamp('deleted_at')->after('updated_at')->change();
        });
    }
};
