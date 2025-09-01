<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'accounts';

    protected $fillable = [
        'account_type_id',
        'report_group_id',
        'account_number',
        'account_name',
        'taxable',
        'account_description',
        'bank_reconciliation',
        'is_active',
        'statement',
    ];

    protected function casts(): array
    {
        return [
            'taxable' => 'boolean',
        ];
    }

    public $timestamps = true;

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function terms(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function reportGroup(): BelongsTo
    {
        return $this->belongsTo(ReportGroup::class);
    }

    public function journalEntryDetails(): HasMany
    {
        return $this->hasMany(JournalDetails::class);
    }

    public function scopeWithAccountType($query)
    {
        return $query->with('accountType');
    }

    public function scopeWithReportGroup($query)
    {
        return $query->with('reportGroup');
    }

    public function getFullAccountAttribute(): string
    {
        $reportGroup = $this->reportGroup ? " - {$this->reportGroup->name}" : '';

        return "{$this->account_number} - {$this->account_name} $reportGroup";
    }

    public function scopeAccountName(Builder $query, string $accountName): Builder
    {
        return $query->where('account_name', $accountName);
    }

    public function scopeTaxable(Builder $query, bool $state = true): Builder
    {
        return $query->where('taxable', $state);
    }


}
