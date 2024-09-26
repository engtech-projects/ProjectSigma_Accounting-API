<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

	protected $primaryKey = "id";

    protected $fillable = [
		'voucher_no',
		'payee',
		'particulars',
		'net_amount',
		'amount_in_words',
		'date_encoded',
		'voucher_date',
		'status'
    ];
}
