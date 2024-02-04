<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "category_id";
    protected $fillable = [
        'category_name',
        'to_increase'
    ];

    ## MODEL RELATIONS##

    public function account_type(): HasMany
    {
        return $this->hasMany(AccountType::class,'type_id');
    }
    ## MODEL SCOPES BINDING ##
}
