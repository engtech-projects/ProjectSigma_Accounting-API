<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case OPEN = "open";
    case UNPOSTED = "unposted";
}
