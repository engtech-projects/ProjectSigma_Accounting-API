<?php

namespace App\Models;

use App\Enums\Reports\BalanceSheet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ParticularsType;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

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
            'is_active' => 'boolean',
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
    public function subGroup(): BelongsTo
    {
        return $this->belongsTo(SubGroup::class);
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
    public function scopeWithSubGroup($query)
    {
        return $query->with('subGroup');
    }

    public function getFullAccountAttribute(): string
    {
        $reportGroup = $this->reportGroup ? " - {$this->reportGroup->name}" : '';
        $subGroup = $this->subGroup ? " - {$this->subGroup->name}" : '';

        return "{$this->account_number} - {$this->account_name} $reportGroup $subGroup";
    }

    public function scopeAccountName(Builder $query, string $accountName): Builder
    {
        return $query->where('account_name', $accountName);
    }

    public function scopeTaxable(Builder $query, bool $state = true): Builder
    {
        return $query->where('taxable', $state);
    }

    public function scopecashInBank($accountBalances)
    {
        return $accountBalances->whereHas('accounts', function ($accountBalances) {
            $accountBalances->where('account_type', ParticularsType::CASH_IN_BANK->value);
        });
    }

    public function scopecurrentAssets($query)
    {
        return $query->whereHas('subGroup', function ($q) {
            $q->where('name', BalanceSheet::CURRENT_ASSET->value);
        });
    }
    public function scopenonCurrentAssets($query)
    {
        return $query->whereHas('subGroup', function ($q) {
            $q->where('name', BalanceSheet::NON_CURRENT_ASSET->value);
        });
    }

    public function scopecurrentLiabilities($query)
    {
        return $query->whereHas('subGroup', function ($q) {
            $q->where('name', BalanceSheet::CURRENT_LIABILITIES->value);
        });
    }

    public function scopeEquity($query)
    {
        return $query->whereHas('subGroup', function ($q) {
            $q->where('name', BalanceSheet::EQUITY->value);
        });
    }
}
