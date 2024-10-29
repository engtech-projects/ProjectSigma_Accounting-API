<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

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
	
	public static function generatePrfNo()
	{	
		$prefix = strtoupper('RFA-ACCTG');
		$currentYearMonth = Carbon::now()->format('Y-m'); 
        // Find the highest series
        $lastPaymentRequest = PaymentRequest::where('prf_no', 'like', "{$prefix}-{$currentYearMonth}-%")
            ->orderBy('prf_no', 'desc')
            ->first();
        // Extract the last series number if a previous request exists
        if ($lastPaymentRequest) {
            $lastSeries = (int) substr($lastPaymentRequest->prf_no, -4); // Get last 4 digits
            $nextSeries = $lastSeries + 1;
        } else {
            $nextSeries = 1; // Start at 0001 if no previous voucher
        }
        // Format the series number to be 4 digits (e.g., 0001)
        $paddedSeries = str_pad($nextSeries, 4, '0', STR_PAD_LEFT);
        // Construct the new reference number
        $prfNo = "{$prefix}-{$currentYearMonth}-{$paddedSeries}";

        return $prfNo;
	}

	public function forms()
    {
        return $this->morphMany(Form::class, 'formable');
    }

	public function vouchers()
	{
		return $this->morphMany(Voucher::class, 'formable');
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
}