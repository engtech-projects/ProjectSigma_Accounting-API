<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FiscalYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fiscal_year';

    public $timestamps = true;

    protected $fillable = [
        'period_start',
        'period_end',
        'status',
    ];

    public function postingPeriods(): HasMany
    {
        return $this->hasMany(PostingPeriod::class);
    }

    public function scopeCurrent($query)
    {
        $currentDate = Carbon::now();

        return $query
            ->where('period_start', '<=', $currentDate)
            ->where('period_end', '>=', $currentDate);
    }

    public static function currentPostingPeriod()
    {
        return self::current()->pluck('id')->first();
    }

    public function scopeHasJournalEntries($query)
    {
        return $query->whereHas('postingPeriods', function ($subQuery) {
            $subQuery->whereHas('journalEntries');
        });
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['postingPeriods' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);
    }
}
