<?php

namespace App\Models;
use App\Http\Traits\HasApproval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Http\Traits\ModelHelpers;
class PaymentRequest extends Model
{
    use HasFactory, SoftDeletes, HasApproval, ModelHelpers, Notifiable;
	protected $table = 'payment_request';
	protected $fillable = [
		'stakeholder_id',
		'prf_no',
		'request_date',
		'description',
		'total',
		'approvals',
        'created_by',
        'request_status',
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
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
	public function scopePrfNo($query, $prfNo)
	{
		return $query->where('prf_no', $prfNo);
	}
    public function scopeWithPaymentRequestDetails($query)
    {
        return $query->with(['details.stakeholder']);
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
    public function scopeOrderByDesc($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    public function scopeWithCreatedBy($query)
    {
        return $query->with('created_by_user');
    }
}
