<?php

namespace App\Models;

use App\Models\Pivot\BookAccount;
use App\Models\Pivot\AccountGroupBook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory, SoftDeletes;


    protected $primaryKey = "book_id";

    protected $fillable = [
        "book_code",
        "book_name",
        "symbol"
    ];


    ## MODEL RELATION ##

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'book_accounts', 'book_id', 'account_id')
            ->using(BookAccount::class)
            ->withPivot(['book_id', 'account_id'])
            ->withTimestamps();
    }
    public function account_group_books(): BelongsToMany
    {
        return $this->belongsToMany(AccountGroup::class, 'account_group_books', 'book_id', 'account_group_id')
            ->using(AccountGroupBook::class)
            ->withPivot(['book_id', 'account_group_id'])
            ->withTimestamps();
    }


    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
