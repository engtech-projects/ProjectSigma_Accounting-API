<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum JournalStatus: string
{
    use EnumHelper;

    case DRAFTED = 'drafted';
    case OPEN = 'open';
    case POSTED = 'posted';
    case UNPOSTED = 'unposted';
    case FOR_PAYMENT = 'for_payment';
    case VOID = 'void';
}
