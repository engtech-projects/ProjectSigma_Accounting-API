<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum TransactionFlowStatus: string
{
    use EnumHelper;

    case PENDING = 'pending';
    case DONE = 'done';
}
