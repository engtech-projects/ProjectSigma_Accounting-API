<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalDetails extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'journal_details';

    protected $timestamp = false;

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'stakeholder_id',
        'description',
        'debit',
        'credit',
    ];

    protected $casts = [
        'debit' => 'float',
        'credit' => 'float',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class);
    }
}
