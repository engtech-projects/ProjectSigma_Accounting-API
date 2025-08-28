<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\PostingPeriodStatusType;

class PostingPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'posting_periods';

    public $timestamps = true;

    protected $fillable = [
        'period_start',
        'period_end',
        'status',
    ];

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }

    public function scopeCurrent($query)
    {
        $currentDate = Carbon::now();

        return $query->whereHas('periods', function ($subQuery) use ($currentDate) {
            $subQuery->where('period_start', '<=', $currentDate)
                ->where('period_end', '>=', $currentDate);
        });
    }

    public static function currentPostingPeriod()
    {
        return self::current()->pluck('id')->first();
    }

    public function scopeHasJournalEntries($query)
    {
        return $query->whereHas('periods', function ($subQuery) {
            $subQuery->whereHas('journalEntries');
        });
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['periods' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);
    }

    public function scopeCheckIfStatusIsOpenYearly($currentMonth = null)
    {
        if ($this->status === PostingPeriodStatusType::OPEN->value) {
            $subPeriodsQuery = $this->periods();
            if ($currentMonth) {
                $subPeriodsQuery->where('start_date', '!=', $currentMonth->startOfMonth());
            }
    
            if ($subPeriodsQuery->where('status', PostingPeriodStatusType::OPEN->value)->exists()) {
                return true;
            } else {
                $this->update([
                    'status' => PostingPeriodStatusType::CLOSED,
                ]);
                return false;
            } 
        }
        return false;
    }
}
