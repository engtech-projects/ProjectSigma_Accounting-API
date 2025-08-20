<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'voucher_details';

    protected $foreignKey = 'voucher_id';

    protected $fillable = [
        'voucher_id',
        'account_id',
        'stakeholder_id',
        'debit',
        'credit',
    ];

    public function voucher(): BelongsTo
    {
        return $this->BelongsTo(Voucher::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class);
    }
}
