<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum ApprovalPayementRequestType: string
{
    use EnumHelper;

    case APPROVAL_PAYMENT_REQUEST_NPO = 'Payment Request Form (NPO)';
    case APPROVAL_PAYMENT_REQUEST_PO = 'Payment Request Form (PO)';
    case APPROVAL_PAYMENT_REQUEST_PAYROLL = 'Payment Request Form (PAYROLL)';
    case APPROVAL_DISBURSEMENT_VOUCHER = 'Disbursement Voucher Request';
    case APPROVAL_CASH_VOUCHER = 'Cash Voucher Request';
}
