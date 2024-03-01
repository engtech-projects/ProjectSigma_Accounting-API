<?php

namespace App\Traits;

use App\Models\Book;
use App\Models\Pivot\AccountGroupBook;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasBook
{
    public function account_book_group(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'account_group_books', 'book_id', 'account_group_id')
            ->using(AccountGroupBook::class)
            ->withPivot(['account_group_id', 'book_id'])
            ->withTimestamps();
    }

}
