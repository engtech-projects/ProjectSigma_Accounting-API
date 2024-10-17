<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        "date_encoded" => 'date:Y-m-d',
        "journal_date" => 'date:Y-m-d',
    ];

	public function details(): HasMany
    {
        return $this->hasMany(JournalDetails::class);
    }

}
