<?php

namespace App\Enums;

use App\Models\RequestBOM;
use App\Models\RequestItemProfiling;
use App\Models\WarehouseTransaction;

enum ApprovalModels: string
{
    case ACCOUNTING_PAYMENT_REQUEST = PaymentRequest::class;
    case ACCOUNTING_DISBURSEMENT_REQUEST = DisbursementRequest::class;
    case ACCOUNTING_CASH_REQUEST = CashVoucher::class;

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->name] = $case->value;
        }
        return $array;
    }

    public static function toArraySwapped(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->name;
        }
        return $array;
    }
}
