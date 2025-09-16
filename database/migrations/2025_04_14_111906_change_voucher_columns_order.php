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
        // Change columns order in voucher table
        Schema::table('voucher', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('receipt_no')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore default columns order in voucher table
        Schema::table('voucher', function (Blueprint $table) {
            $table->id();
            $table->string('check_no')->nullable()->after('id')->change();
            $table->string('voucher_no')->after('check_no')->change();
            $table->bigInteger('stakeholder_id')->after('voucher_no')->change();
            $table->text('particulars')->after('stakeholder_id')->change();
            $table->double('net_amount')->after('particulars')->change();
            $table->text('amount_in_words')->nullable()->after('net_amount')->change();
            $table->enum('type', ['Cash', 'Disbursement'])->after('amount_in_words')->change();
            $table->date('voucher_date')->after('type')->change();
            $table->date('date_encoded')->after('voucher_date')->change();
            $table->bigInteger('book_id')->after('date_encoded')->change();
            $table->string('reference_no')->after('book_id')->change();
            $table->json('approvals')->after('reference_no')->change();
            $table->string('request_status')->after('approvals')->change();
            $table->int('journal_entry')->after('request_status')->change();
            $table->string('received_by')->nullable()->after('journal_entry')->change();
            $table->date('received_date')->nullable()->after('received_by')->change();
            $table->string('attach_file')->nullable()->after('received_date')->change();
            $table->int('created_by')->after('attach_file')->change();
            $table->int('receipt_no')->after('created_by')->change();
            $table->timestamp('created_at')->nullable()->after('receipt_no')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
