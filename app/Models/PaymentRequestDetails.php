<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRequestDetails extends Model
{
    use HasFactory;

	protected $table = 'payment_request_details';

	protected $fillable = [
		'payment_request_id',
		'stakeholder_id',
		'particulars',
		'cost',
		'vat',
		'amount'
	];

	public function paymentRequest(): BelongsTo
    {
        return $this->BelongsTo(PaymentRequest::class);
    }
}