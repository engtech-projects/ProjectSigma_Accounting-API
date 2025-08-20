<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TransactionLogStatus: string
{
    use EnumHelper;

    case REQUEST = 'request';
    case CHECK_LIST = 'checkList';
    case VOUCHER = 'voucher';
    case JOURNAL = 'journal';
    case APPROVAL = 'approval';
    case PAYMENT = 'payment';
    case ATTACHMENT = 'attachment';
    case BUDGET = 'budget';
}
