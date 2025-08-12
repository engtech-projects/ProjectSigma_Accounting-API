<?php

namespace Database\Seeders;

use App\Enums\TransactionFlowStatus;
use App\Models\TransactionFlowModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionFlowModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionFlowModel::truncate();
        TransactionFlowModel::updateOrCreate([
            'name' => 'Create Payment Request',
            'unique_name' => 'create_payment_request',
            'category' => 'prf',
            'description' => 'Create Payment Request',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '1',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Check Documents',
            'unique_name' => 'check_documents',
            'category' => 'prf',
            'description' => 'Check Documents',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '2',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Check and sign Disbursement Check List',
            'unique_name' => 'check_and_sign_disbursement_check_list',
            'category' => 'prf',
            'description' => 'Check and sign Disbursement Check List',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '3',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Check Request Budget',
            'unique_name' => 'check_request_budget',
            'category' => 'prf',
            'description' => 'Check Request Budget',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '4',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'PRF Approval',
            'unique_name' => 'prf_approval',
            'category' => 'prf',
            'description' => 'PRF Approval',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '5',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Create Journal Entry',
            'unique_name' => 'create_journal_entry',
            'category' => 'journal_entry',
            'description' => 'Create Journal Entry',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '6',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Generate Disbursement Voucher',
            'unique_name' => 'generate_disbursement_voucher',
            'category' => 'disbursement_voucher',
            'description' => 'Generate Disbursement Voucher',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => ' 7',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Check and Review Disbursement Voucher',
            'unique_name' => 'check_and_review_disbursement_voucher',
            'category' => 'disbursement_voucher',
            'description' => 'Check and Review Disbursement Voucher',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '8',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Disbursement Voucher Approval',
            'unique_name' => 'disbursement_voucher_approval',
            'category' => 'disbursement_voucher',
            'description' => 'Disbursement Voucher Approval',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '9',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Generate Cash Voucher',
            'unique_name' => 'generate_cash_voucher',
            'category' => 'cash_voucher',
            'description' => 'Generate Cash Voucher',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '10',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Cash Voucher Approvals',
            'unique_name' => 'cash_voucher_approvals',
            'category' => 'cash_voucher',
            'description' => 'Cash Voucher Approvals',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '11',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        TransactionFlowModel::updateOrCreate([
            'name' => 'Payments',
            'unique_name' => 'cash_voucher',
            'category' => 'payments',
            'description' => 'Payments',
            'status' => TransactionFlowStatus::PENDING,
            'priority' => '12',
        ], [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
