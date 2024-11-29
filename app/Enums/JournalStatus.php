<?php

namespace App\Enums;

enum JournalStatus: string
{
    case DRAFTED = 'drafted';
    case UNPOSTED = 'unposted';
    case POSTED = 'posted';
    case VOID = 'void';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
