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
        //Change columns order in payment_request_details table
        Schema::table('payment_request_details', function (Blueprint $table) {
            $table->timestamp('created_at')->nullable()->after('particular_group_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Restore default columns order in payment_request_details table
        Schema::table('payment_request_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('payment_request_id')->after('id')->change();
            $table->tinyText('particulars')->nullable()->after('payment_request_id')->change();
            $table->decimal('cost', 10, 2)->nullable()->after('particulars')->change();
            $table->decimal('vat', 10, 2)->nullable()->after('cost')->change();
            $table->decimal('amount', 10, 2)->nullable()->after('vat')->change();
            $table->decimal('total_vat_amount', 10, 2)->nullable()->after('amount')->change();
            $table->bigInteger('stakeholder_id')->nullable()->after('total_vat_amount')->change();
            $table->bigInteger('particular_group_id')->nullable()->after('stakeholder_id')->change();
            $table->timestamp('created_at')->nullable()->after('particular_group_id')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }
};
