<?php

namespace App\Models\Pivot;

use App\Models\Account;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AccountHasGroup extends Pivot
{

    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'account_id');
    }
}
