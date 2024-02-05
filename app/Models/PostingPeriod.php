<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostingPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "period_start",
        "period_end",
        "status"
    ];

    protected $casts = [
        "period_start" => 'date:Y-m-d',
        "period_end" => 'date:Y-m-d',
        'status' => PostingPeriodStatus::class,
    ];
}
