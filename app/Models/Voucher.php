<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory;

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
		'book_id'
	];

	protected $casts = [
        "date_encoded" => 'date:Y-m-d',
        "voucher_date" => 'date:Y-m-d',
    ];

	public static function generateVoucherNo($prefix)
	{	
		$prefix = strtoupper($prefix);
		$currentYearMonth = Carbon::now()->format('Ym'); 
        // Find the highest series
        $lastVoucher = Voucher::where('voucher_no', 'like', "{$prefix}-{$currentYearMonth}-%")
            ->orderBy('voucher_no', 'desc')
            ->first();
        // Extract the last series number if a previous voucher exists
        if ($lastVoucher) {
            $lastSeries = (int) substr($lastVoucher->voucher_no, -4); // Get last 4 digits
            $nextSeries = $lastSeries + 1;
        } else {
            $nextSeries = 1; // Start at 0001 if no previous voucher
        }
        // Format the series number to be 4 digits (e.g., 0001)
        $paddedSeries = str_pad($nextSeries, 4, '0', STR_PAD_LEFT);
        // Construct the new reference number
        $voucherNo = "{$prefix}-{$currentYearMonth}-{$paddedSeries}";

        return $voucherNo;
	}

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
        return $this->belongsTo(Stakeholder::class);
    }
}
