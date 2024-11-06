<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\JournalStatus;

class JournalEntry extends Model
{
    use HasFactory;

	protected $table = 'journal_entry';

	protected $fillable = [
		'journal_no',
		'journal_date',
		'voucher_id',
		'status',
		'remarks',
		'posting_period_id',
		'period_id',
		'reference_no'
	];

	protected $casts = [
        "journal_date" => 'date:Y-m-d',
    ];

	public function details(): HasMany
    {
        return $this->hasMany(JournalDetails::class);
    }

	public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }

	public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

	public function updateStatus($newStatus) : bool
	{
		if ($this->status === $newStatus->value) {
            return false;
        }

		$this->status = $newStatus->value;
        return $this->save();
	}
}