<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Voucher;
use App\Models\Account;
use Illuminate\Database\Eloquent\Casts\Attribute;

class VoucherLineItems extends Model
{
    use HasFactory;

	protected $table = "voucher_line_items";
	protected $primaryKey = "id";

    protected $fillable = [
		'voucher_id',
		'account_id',
		'contact',
		'debit',
		'credit'
    ];

	public function voucher(): BelongsTo
    {
        return $this->BelongsTo(Voucher::class);
    }
	
	public function account(): HasOne
    {
        return $this->hasOne(Account::class);
    }
}
