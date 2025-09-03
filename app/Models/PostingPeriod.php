<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostingPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'posting_periods';

    public $timestamps = true;

    protected $fillable = [
        'fiscal_year_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class, 'id');
    }

    public function scopeCurrent($query)
    {
        $currentDate = Carbon::now();
        return $query
            ->where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate);
    }

    public static function currentPostingPeriod()
    {
        return self::current()->pluck('id')->first();
    }

    public function scopeHasJournalEntries($query)
    {
        return $query->whereHas('fiscalYear', function ($subQuery) {
            $subQuery->whereHas('journalEntries');
        });
    }

    public function scopeWithDetails($query)
    {
        return $query->with('fiscalYear')
            ->orderBy('created_at');
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }
}
