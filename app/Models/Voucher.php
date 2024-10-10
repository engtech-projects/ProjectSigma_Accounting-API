<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

	protected $table = "voucher";
	protected $primaryKey = "id";

	public const STATUS_PENDING = 'pending';
	public const STATUS_DRAFT = 'draft';
	public const STATUS_APPROVED = 'approved';
	public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
		'voucher_no',
		'payee',
		'particulars',
		'net_amount',
		'amount_in_words',
		// 'created_by',
		'date_encoded',
		'voucher_date',
		'status',
		// added columns
		'voucher_type',
		'check_no',
		'account_id'
    ];

	
    protected $casts = [
        "date_encoded" => 'date:Y-m-d',
        "voucher_date" => 'date:Y-m-d',
    ];

	public function Items(): HasMany
    {
        return $this->hasMany(VoucherLineItems::class);
    }
}
