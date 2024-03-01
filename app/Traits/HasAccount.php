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
    public function account_group(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_has_group', 'account_group_id', 'account_id')
            ->using(AccountHasGroup::class)
            ->withPivot(['account_id', 'account_group_id'])
            ->withTimestamps();
    }

    public function account_book(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'book_accounts', 'book_id', 'account_id')
            ->using(BookAccount::class)
            ->withPivot(['book_id', 'account_id'])
            ->withTimestamps();

    }


}
