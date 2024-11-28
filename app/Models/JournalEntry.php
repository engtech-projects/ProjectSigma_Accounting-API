<?php

namespace App\Models;

use App\Http\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Traits\HasTransitions;
use Illuminate\Database\Eloquent\SoftDeletes;
class JournalEntry extends Model
{
    use HasFactory, HasTransitions, SoftDeletes, ModelHelpers;
	protected $table = 'journal_entry';
	protected $fillable = [
		'journal_no',
		'journal_date',
		'status',
		'remarks',
		'posting_period_id',
		'period_id',
		'reference_no'
	];
	protected $casts = [
        "journal_date" => 'date:Y-m-d',
    ];
	public function details(): HasMany
    {
        return $this->hasMany(JournalDetails::class);
    }
	public function voucher(): HasMany
	{
		return $this->hasMany(Voucher::class, 'journal_entry_id');
	}
	public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function paymentRequest(): BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class, 'payment_request_id');
    }
    public function scopeWithPaymentRequest($query)
    {
        return $query->with('paymentRequest.stakeholder');
    }
    public function scopeWithDetails($query)
    {
        return $query->with('details.stakeholder');
    }
    public function scopeWithAccounts($query)
    {
        return $query->with('details.account');
    }
}
