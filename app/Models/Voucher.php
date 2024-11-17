<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Traits\HasTransitions;
use App\Http\Traits\HasApprovals;
class Voucher extends Model
{
    use HasFactory, HasTransitions, SoftDeletes, HasApprovals;

	protected $table = 'voucher';

	protected $fillable = [
		'check_no',
		'voucher_no',
		'stakeholder_id',
		'particulars',
		'net_amount',
		'amount_in_words',
		'status',
		'voucher_date',
		'date_encoded',
		'account_id',
		'book_id',
		'reference_no',
		'formable_id',
		'formable_type'
	];

	protected $casts = [
        "date_encoded" => 'date:Y-m-d',
        "voucher_date" => 'date:Y-m-d',
		'approvals' => 'array',
    ];

	public function account() : BelongsTo
	{
		return $this->belongsTo(Account::class);
	}

	public function details(): HasMany
    {
        return $this->hasMany(VoucherDetails::class);
    }

	public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

	public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class);
    }

	public function scopeFilterBook($query, $bookId)
    {
        return $query->where('book_id', $bookId);
    }

	public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
