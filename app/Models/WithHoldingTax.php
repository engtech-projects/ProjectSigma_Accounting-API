<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithHoldingTax extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'withholding_tax';

    protected $fillable = [
        'account_id',
        'wtax_name',
        'vat_type',
        'wtax_percentage',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
