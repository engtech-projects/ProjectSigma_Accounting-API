<?php

namespace App\Traits;

use App\Models\AccountGroup;
use App\Models\Pivot\AccountHasGroup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasGroup
{

    public function account_group(): BelongsToMany
    {
        return $this->belongsToMany(AccountGroup::class, 'account_has_group', 'account_id', 'account_group_id')
            ->using(AccountHasGroup::class)
            ->withPivot(['account_id', 'account_group_id'])
            ->withTimestamps();
    }
}
