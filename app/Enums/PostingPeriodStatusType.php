<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum PostingPeriodStatusType: string
{
    use EnumHelper;

    case OPEN = 'open';
    case CLOSED = 'closed';
}
