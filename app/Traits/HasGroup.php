<?php

namespace App\Traits;

use App\Models\AccountGroup;
use App\Models\Book;
use App\Models\Pivot\AccountGroupBook;
use App\Models\Pivot\AccountHasGroup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasGroup
{
    public function account_group(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class);
    }

}
