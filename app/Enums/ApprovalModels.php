<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;
use App\Models\CashRequest;
use App\Models\DisbursementRequest;
use App\Models\PaymentRequest;
use App\Models\TransactionFlow;

enum ApprovalModels: string
{
    use EnumHelper;

    case ACCOUNTING_PAYMENT_REQUEST = PaymentRequest::class;
    case ACCOUNTING_DISBURSEMENT_REQUEST = DisbursementRequest::class;
    case ACCOUNTING_CASH_REQUEST = CashRequest::class;
    case ACCOUNTING_TRANSACTION = TransactionFlow::class;
}
