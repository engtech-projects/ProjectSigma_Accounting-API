<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidationAllocation extends Model
{
    use HasFactory;
    protected $table = 'liquidation_allocation';
    protected $fillable = [
        'description',
        'allocation',
    ];
}
