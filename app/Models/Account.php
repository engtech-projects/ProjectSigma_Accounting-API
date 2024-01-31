<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_name',
        'account_number',
        'account_description',
        'status',
        'parent_account',
        'bank_reconciliation',
        'statement',
        'type',
        'account_type_id',
    ];

    ## MODEL RELATIONS ##

    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }



    ## MODEL SCOPE BINDINGS ##

    public function scopeWithRelation($query, $relation = [])
    {
        return $query->with($relation);
    }

    public function scopeWithPaginate($query,$perPage = 10)
    {
        return $query->paginate($perPage);
    }
}
