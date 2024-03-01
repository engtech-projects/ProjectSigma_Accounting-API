<?php

namespace App\Traits;

use App\Models\Book;
use App\Models\Pivot\AccountGroupBook;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasBook
{
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

}
