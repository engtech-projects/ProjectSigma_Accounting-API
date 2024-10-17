<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherDetails extends Model
{
    use HasFactory;

	protected $table = 'voucher_details';

	protected $fillable = [
		'voucher_id',
		'account_id',
		'stakeholder_id',
		'debit',
		'credit'
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
        return $this->belongsTo(Stakeholder::class);
    }
}
