<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "type_id";

    protected $fillable = [
        'type_name',
        'type_number',
        'has_opening_balance',
        'category_id',
    ];


    ## MODEL RELATIONS ##

    public function account(): HasMany
    {
        return $this->hasMany(Account::class,'account_id');
    }

    public function account_category(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class,'category_id');
    }



    ## MODEL SCOPES BINDING ##

}
