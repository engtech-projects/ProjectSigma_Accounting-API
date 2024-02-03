<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "book_code",
        "book_name",
        "book_src",
        "bok_ref",
        "book_head",
        "book_flag",
    ];


    ## MODEL RELATION ##

    public function book_has_accounts() : BelongsToMany
    {
        return $this->belongsToMany(Account::class,'account_book','book_id','account_id')->withTimestamps();

    }


    ## MODEL SCOPE BINDINGS ##
}
