<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;
use App\Traits\HasFormable;

class PaymentRequest extends Model
{
    use HasFactory,  HasFormable;
	protected $table = 'payment_request';
	protected $fillable = [
		'stakeholder_id',
		'prf_no',
		'request_date',
		'description',
		'total',
	];
	public function form()
    {
        return $this->morphOne(Form::class, 'formable');
    }
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
}
