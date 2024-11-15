<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
class PaymentRequestDetails extends Model
{
    use HasFactory, SoftDeletes;
	protected $table = 'payment_request_details';
	protected $fillable = [
		'payment_request_id',
		'particulars',
		'cost',
		'vat',
        'stakeholder_id',
		'amount'
	];
	public function paymentRequest(): BelongsTo
    {
        return $this->BelongsTo(PaymentRequest::class);
    }
	public function stakeholder(): BelongsTo
	{
		return $this->belongsTo(StakeHolder::class);
	}
}
