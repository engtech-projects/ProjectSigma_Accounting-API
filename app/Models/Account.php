<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Enums\PostingPeriodStatus;
use App\Models\Pivot\AccountHasGroup;
use App\Models\Pivot\BookAccount;
use App\Traits\HasGroup;
use App\Traits\ModelGlobalScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Account extends Model
{
    use HasFactory, SoftDeletes, ModelGlobalScope;
    use HasGroup;

    protected $table = "accounts";
    protected $primaryKey = "account_id";
    protected $fillable = [
        'account_name',
        'account_number',
        'account_description',
        'status',
        'type_id',
    ];

    protected $casts = [
        'status' => AccountStatus::class,

    ];


    ## MODEL RELATIONS ##

    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'type_id')->withDefault();
    }

    public function book_accounts(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_accounts')
            ->using(BookAccount::class)
            ->withPivot(['account_id', 'book_id']);

    }

    public function opening_balance(): HasMany
    {
        return $this->hasMany(OpeningBalance::class, 'account_id')
            ->whereRelation('posting_period', 'status', PostingPeriodStatus::STATUS_OPEN);
    }


    ## MODEL SCOPE BINDINGS ##

    // LOCAL SCOPES
    public function scopeActiveAccount(Builder $query): void
    {
        $query->where('status', AccountStatus::ACTIVE);
    }


    //DYNAMIC SCOPES

    public function scopeRelations(Builder $query, array $relations = []): void
    {
        $query->with($relations);
    }

}
