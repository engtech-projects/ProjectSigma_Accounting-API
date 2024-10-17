<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalDetails extends Model
{
    use HasFactory;

	protected $table = 'journal_details';
	protected $timestamp = false;

	protected $fillable = [
		'journal_id',
		'account_id',
		'stakeholder_id',
		'description',
		'debit',
		'credit',
    ];

	public function account() : BelongsTo
	{
		$this->belongsTo(Account::class);
	}

}
