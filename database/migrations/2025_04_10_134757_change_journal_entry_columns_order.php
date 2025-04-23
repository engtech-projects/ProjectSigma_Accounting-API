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
        //Change columns order in journal_entry table
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('entry_date')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Restore default columns order in journal_entry table
        Schema::table('journal_entry', function (Blueprint $table) {
            $table->id();
            $table->string('journal_no')->after('id')->change();
            $table->date('journal_date')->after('journal_no')->change();
            $table->enum('status', ['for_payment', 'unposted', 'posted', 'void', 'drafted', 'open'])->after('journal_date')->change();
            $table->bigInteger('posting_period_id')->after('status')->change();
            $table->bigInteger('period_id')->after('posting_period_id')->change();
            $table->text('remarks')->nullable()->after('period_id')->change();
            $table->string('reference_no')->after('remarks')->change();
            $table->bigInteger('payment_request_id')->after('reference_no')->change();
            $table->integer('created_by')->after('payment_request_id')->change();
            $table->date('entry_date')->after('created_by')->change();
            $table->timestamp('created_at')->nullable()->after('entry_date')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
