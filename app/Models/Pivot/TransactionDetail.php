<?php

namespace App\Models\Pivot;

use App\Models\Account;
use App\Models\StakeHolder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionDetail extends Pivot
{
    protected $table = "transaction_details";
    protected $primaryKey = "transaction_detail_id";
    protected $fillable = [];

    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'account_id', 'account_id');
    }
    public function stakeholder(): HasOne
    {
        return $this->hasOne(StakeHolder::class, 'stakeholder_id', 'stakeholder_id');
    }
}
