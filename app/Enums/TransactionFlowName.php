<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TransactionFlowName: string
{
    use EnumHelper;

    case CREATE_PAYMENT_REQUEST = 'create_payment_request';
    case CREATE_PAYMENT_REQUEST_RECEIVED = 'create_payment_request_received';
    case CHECK_DOCUMENTS = 'check_documents';
    case CHECK_AND_SIGN_DISBURSEMENT_CHECK_LIST = 'check_and_sign_disbursement_check_list';
    case CHECK_REQUEST_BUDGET = 'check_request_budget';
    case PRF_APPROVAL = 'prf_approval';
    case CREATE_JOURNAL_ENTRY = 'create_journal_entry';
    case GENERATE_DISBURSEMENT_VOUCHER = 'generate_disbursement_voucher';
    case CHECK_AND_REVIEW_DISBURSEMENT_VOUCHER = 'check_and_review_disbursement_voucher';
    case DISBURSEMENT_VOUCHER_APPROVAL = 'disbursement_voucher_approval';
    case GENERATE_CASH_VOUCHER = 'generate_cash_voucher';
    case CASH_VOUCHER_APPROVALS = 'cash_voucher_approvals';
    case PAYMENTS = 'payments';
}
