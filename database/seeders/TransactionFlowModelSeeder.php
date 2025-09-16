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
        $flows = [
            ['name' => 'Create Payment Request', 'unique_name' => 'create_payment_request', 'category' => 'prf', 'is_assignable' => false, 'priority' => 1, 'is_passable' => false],
            ['name' => 'Check Documents (PRF)', 'unique_name' => 'check_documents_prf', 'category' => 'prf', 'is_assignable' => true, 'priority' => 2, 'is_passable' => false],
            ['name' => 'Check Documents (PO)', 'unique_name' => 'check_documents_po', 'category' => 'po', 'is_assignable' => true, 'priority' => 2, 'is_passable' => false],
            ['name' => 'Check Documents (Payroll)', 'unique_name' => 'check_documents_payroll', 'category' => 'payroll', 'is_assignable' => true, 'priority' => 2, 'is_passable' => false],
            ['name' => 'Check and sign Disbursement Check List', 'unique_name' => 'check_and_sign_disbursement_check_list', 'category' => 'prf', 'is_assignable' => true, 'priority' => 3, 'is_passable' => false],
            ['name' => 'Check Request Budget', 'unique_name' => 'check_request_budget', 'category' => 'prf', 'is_assignable' => true, 'priority' => 4, 'is_passable' => true],
            ['name' => 'PRF Approval', 'unique_name' => 'prf_approval', 'category' => 'prf', 'is_assignable' => false, 'priority' => 5, 'is_passable' => false],
            ['name' => 'Create Journal Entry', 'unique_name' => 'create_journal_entry', 'category' => 'journal_entry', 'is_assignable' => true, 'priority' => 6, 'is_passable' => false],
            ['name' => 'Generate Disbursement Voucher', 'unique_name' => 'generate_disbursement_voucher', 'category' => 'disbursement_voucher', 'is_assignable' => true, 'priority' => 7, 'is_passable' => false],
            ['name' => 'Check and Review Disbursement Voucher', 'unique_name' => 'check_and_review_disbursement_voucher', 'category' => 'disbursement_voucher', 'is_assignable' => true, 'priority' => 8, 'is_passable' => false],
            ['name' => 'Disbursement Voucher Approval', 'unique_name' => 'disbursement_voucher_approval', 'category' => 'disbursement_voucher', 'is_assignable' => false, 'priority' => 9, 'is_passable' => false],
            ['name' => 'Generate Cash Voucher', 'unique_name' => 'generate_cash_voucher', 'category' => 'cash_voucher', 'is_assignable' => true, 'priority' => 10, 'is_passable' => false],
            ['name' => 'Cash Voucher Approvals', 'unique_name' => 'cash_voucher_approvals', 'category' => 'cash_voucher', 'is_assignable' => false, 'priority' => 11, 'is_passable' => false],
            ['name' => 'Payments', 'unique_name' => 'payments', 'category' => 'payments', 'is_assignable' => true, 'priority' => 12, 'is_passable' => false],
        ];

        $now = Carbon::now();

        foreach ($flows as $flow) {
            TransactionFlowModel::updateOrCreate(
                ['unique_name' => $flow['unique_name']],
                array_merge($flow, [
                    'description' => $flow['name'],
                    'status' => TransactionFlowStatus::PENDING,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
