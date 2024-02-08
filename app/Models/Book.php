<?php

namespace App\Models;

use App\Models\Pivot\BookAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "book_id";

    protected $fillable = [
        "book_code",
        "book_name",
        "book_src",
        "bok_ref",
        "book_head",
        "book_flag",
    ];


    ## MODEL RELATION ##

    public function book_accounts() : BelongsToMany
    {
        return $this->belongsToMany(Account::class,'account_book','book_id','account_id')->using(BookAccount::class)->withPivot(['book_id','account_id'])->withTimestamps();

    }


    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
