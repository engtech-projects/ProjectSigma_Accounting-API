<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category',
        'to_increase'
    ];


    ## MODEL RELATIONS##

    public function account_type(): HasMany
    {
        return $this->hasMany(AccountType::class);
    }
    ## MODEL SCOPES BINDING ##
}
