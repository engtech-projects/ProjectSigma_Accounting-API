<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountGroup extends Pivot
{
    protected $table = 'account_groups';


}
