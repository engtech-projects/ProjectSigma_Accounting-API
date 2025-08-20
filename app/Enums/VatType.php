<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum VatType: string
{
    use EnumHelper;

    case VAT = 'VAT';
    case NON_VAT = 'NON-VAT';

}
