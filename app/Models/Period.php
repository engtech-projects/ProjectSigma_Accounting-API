<?php

namespace App\Models;

use App\Enums\PostingPeriodStatusType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'periods';

    protected $fillable = [
        'period_id',
        'start_date',
        'end_date',
        'status',
    ];

    public $timestamps = true;

    public function postingPeriod(): BelongsTo
    {
        return $this->belongsTo(PostingPeriod::class);
    }

    public function scopeCurrent($query)
    {
        $currentDate = Carbon::now();

        return $query->where('start_date', '<=', $currentDate->toDateString())
            ->where('end_date', '>=', $currentDate->toDateString())
            ->where('status', PostingPeriodStatusType::OPEN->value);
    }
}
