<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentRequest extends Model
{
    use HasFactory, SoftDeletes;
	protected $table = 'payment_request';
	protected $fillable = [
		'stakeholder_id',
		'prf_no',
		'request_date',
		'description',
		'total',
		'approvals',
	];
    protected $casts = [
        'approvals' => 'array',
    ];

	public function details(): HasMany
    {
        return $this->hasMany(PaymentRequestDetails::class);
    }
	public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class);
    }
	public function scopePrfNo($query, $prfNo)
	{
		return $query->where('prf_no', $prfNo);
	}
    public function scopeWithPaymentRequestDetails($query)
    {
        return $query->with(['details']);
    }
	public function scopeFormStatus($query, $status)
	{
		return $query->whereHas('form', function ($query) use ($status) {
			$query->where('forms.status', $status);
		});
    }
    public function scopeWithStakeholder($query)
    {
        return $query->with(['stakeholder']);
    }
    public function scopeWithDetails($query)
    {
        return $query->with(['details.stakeholder']);
    }
}
