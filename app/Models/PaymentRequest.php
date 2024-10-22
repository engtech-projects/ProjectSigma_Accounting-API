<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentRequest extends Model
{
    use HasFactory;

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
}
