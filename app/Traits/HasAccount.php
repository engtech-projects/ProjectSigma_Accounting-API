<?php

namespace App\Traits;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\Pivot\AccountHasGroup;
use App\Models\Pivot\BookAccount;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasAccount
{

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

}
