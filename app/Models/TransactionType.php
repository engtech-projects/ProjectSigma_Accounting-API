<?php

namespace App\Models;

use App\Models\Pivot\BookAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionType extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = "transaction_types";
    protected $primaryKey = "transaction_type_id";

    protected $fillable = [
        "transaction_type_name",
        "book_id",
        "account_id",
        "symbol"
    ];

    public function book_accounts(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_accounts')
            ->using(BookAccount::class)
            ->withPivot(['book_id', 'book_id']);
    }


    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    public function stakeholder_group(): BelongsTo
    {
        return $this->belongsTo(StakeHolderGroup::class, 'stakeholder_group_id', 'stakeholder_group_id');
    }
    public function document_series(): HasOne
    {
        return $this->hasOne(DocumentSeries::class, 'transaction_type_id', 'transaction_type_id');
    }

    ## MODEL RELATIONS ##


    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
