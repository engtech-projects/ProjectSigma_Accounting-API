<?php

namespace App\Enums;

enum ApprovalPayementRequestType: string
{
    case APPROVAL_PAYMENT_REQUEST_NPO = 'Payment Request Form (NPO)';
    case APPROVAL_PAYMENT_REQUEST_PO = 'Payment Request Form (PO)';
    case APPROVAL_PAYMENT_REQUEST_PAYROLL = 'Payment Request Form (PAYROLL)';
    case APPROVAL_DISBURSEMENT_VOUCHER = 'Disbursement Voucher Request';
    case APPROVAL_CASH_VOUCHER = 'Cash Voucher Request';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
