<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OpeningBalance extends Model
{
    use HasFactory, SoftDeletes;
	protected $table = 'opening_balances';
	protected $fillable = [
        'opening_balance',
        'remaining_balance',
        'account_id',
        'period_id'
    ];
}
