<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Models\Pivot\BookAccount;
use App\Traits\ModelGlobalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Account extends Model
{
    use HasFactory, SoftDeletes, ModelGlobalScope;

    protected $primaryKey = "account_id";
    protected $fillable = [
        'account_name',
        'account_number',
        'account_description',
        'status',
        'type_id',
    ];

    protected $casts = [
        'account_name' => 'string',
        'account_number' => 'string',
        'account_description' => 'string',
        'status' => AccountStatus::class,
        'type_id' => 'integer'
    ];


    ## MODEL RELATIONS ##

    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function book_accounts(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_accounts')
            ->using(BookAccount::class)
            ->withPivot(['account_id', 'book_id']);

    }

    public function account_balance(): HasOne
    {
        return $this->hasOne(OpeningBalance::class,'account_id');
    }



    ## MODEL SCOPE BINDINGS ##
    public function scopeActiveAccount($query)
    {
        return $query->where('status', AccountStatus::ACTIVE);
    }

}
