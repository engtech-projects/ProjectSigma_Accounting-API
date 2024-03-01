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
    public function account_group(): BelongsToMany
    {
        return $this->belongsToMany(AccountGroup::class, 'account_has_group', 'account_id', 'account_group_id')
            ->using(AccountHasGroup::class)
            ->withPivot(['account_id', 'account_group_id']);
    }

    public function account_book_group(): BelongsToMany
    {
        return $this->belongsToMany(AccountGroup::class, 'account_group_books', 'book_id', 'account_group_id')
            ->using(AccountGroupBook::class)
            ->withTimestamps();
    }
}
