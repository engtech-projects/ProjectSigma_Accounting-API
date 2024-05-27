<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionDetail extends Pivot
{
    protected $table = "transaction_details";
    protected $primaryKey = "transaction_detail_id";
    protected $fillable = [

    ];
}
