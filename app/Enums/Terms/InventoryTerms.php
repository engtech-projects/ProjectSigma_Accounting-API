<?php

namespace App\Enums\Terms;

enum InventoryTerms: string
{

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
