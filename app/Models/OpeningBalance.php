<?php

namespace App\Models;

use App\Enums\PeriodStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningBalance extends Model
{
    use HasFactory;

    protected $table = "opening_balances";
    protected $primaryKey = "balance_id";
    protected $fillable = [
        "opening_balance",
        "remaining_balance",
        "period_id",
        "account_id",
    ];

    protected $cast = [
        "period_start" => "date:Y-m-d",
        "period_end" => "date:Y-m-d",
        "status" => PeriodStatus::class,
        "period_id" => 'integer',
        "account_id" => 'integer'
    ];

    ## MODEL RELATIONS ##

    public function posting_period(): BelongsTo
    {
        return $this->belongsTo(PostingPeriod::class, 'period_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

}
