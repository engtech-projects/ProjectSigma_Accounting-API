<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Period extends Model
{
    use HasFactory;

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
		
		return $query->where('start_date', '<=', $currentDate)
					 ->where('end_date', '>=', $currentDate)
					 ->where('status', 'open');
	}
}
