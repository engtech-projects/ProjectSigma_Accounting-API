<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
class PostingPeriod extends Model
{
    use HasFactory, SoftDeletes;

	protected $table = 'posting_periods';
	public $timestamps = false;
	protected $fillable = [
		'period_start',
		'period_end',
		'status',
	];

	public function periods() : HasMany
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
}
