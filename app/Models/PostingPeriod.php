<?php

namespace App\Models;

use App\Enums\PostingPeriodStatus;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostingPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "posting_periods";
    protected $primaryKey = "period_id";

    protected $fillable = [
        "period_start",
        "period_end",
        "status"
    ];

    protected $casts = [
        "period_start" => 'datetime:Y-m-d',
        "period_end" => 'datetime:Y-m-d',
        'status' => PostingPeriodStatus::class,
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }



    ## MODEL RELATION ##




    ## MODEL SCOPE BINDING ##

    public function scopePostingPeriodOpen($query)
    {
        return $query->where('status', PostingPeriodStatus::STATUS_OPEN);
    }

}
