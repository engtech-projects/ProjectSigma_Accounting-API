<?php

namespace App\Enums;

enum JournalStatus: string
{
    case DRAFTED = 'drafted';
    case OPEN = 'open';
    case POSTED = 'posted';
    case UNPOSTED = 'unposted';
    case VOID = 'void';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
