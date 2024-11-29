<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'account_types';

    public $timestamps = false;

    protected $fillable = [
        'account_type',
        'account_category',
        'balance_type',
        'notation',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
