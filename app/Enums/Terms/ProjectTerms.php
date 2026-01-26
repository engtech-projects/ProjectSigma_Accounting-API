<?php

namespace App\Enums\Terms;

enum ProjectTerms: string
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
