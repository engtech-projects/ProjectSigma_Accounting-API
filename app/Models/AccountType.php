<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_type',
        'account_type_number',
        'has_opening_balance',
        'account_type_id',
    ];


    ## MODEL RELATIONS ##

    public function account_category(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class);
    }



    ## MODEL SCOPES BINDING ##

}
