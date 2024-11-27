<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Period extends Model
{
    use HasFactory, SoftDeletes;
	protected $table = 'periods';
	protected $fillable = [
		'period_id',
		'start_date',
		'end_date',
		'status'
	];
	public $timestamps = false;

	public function postingPeriod() : BelongsTo
	{
		return $this->belongsTo(PostingPeriod::class);
	}

	public function scopeCurrent($query)
	{
		$currentDate = Carbon::now();

		return $query->where('start_date', '<=', $currentDate->toDateString())
            ->where('end_date', '>=', $currentDate->toDateString())
            ->where('status', 'open');
	}
}
