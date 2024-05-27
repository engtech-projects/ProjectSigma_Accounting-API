<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case POSTED = "posted";
    case UNPOSTED = "unposted";
}
