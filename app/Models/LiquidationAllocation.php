<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiquidationAllocation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'liquidation_allocation';
    protected $fillable = [
        'description',
        'allocation',
    ];
    protected $casts = [
        'allocation' => 'integer',
    ];
}
