<?php

namespace App\Models;

use App\Enums\AccountCategory;
use App\Enums\BalanceType;
use App\Enums\Notation;
use App\Traits\ModelGlobalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "type_id";

    protected $fillable = [
        'account_type',
        'account_category',
        'balance_type',
        'notation',
    ];

    protected $casts = [
        'acccount_type' => 'string',
        'account_category' => AccountCategory::class,
        'balance_type' => BalanceType::class,
        'notation' => Notation::class,
    ];


    ## MODEL RELATIONS ##

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'type_id');
    }





    ## MODEL SCOPES BINDING ##

}
