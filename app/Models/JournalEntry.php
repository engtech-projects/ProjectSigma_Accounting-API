<?php

namespace App\Models;

use App\Enums\JournalStatus;
use App\Enums\RequestStatuses;
use App\Http\Traits\HasApproval;
use App\Http\Traits\HasTransitions;
use App\Http\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use HasApproval, HasFactory, HasTransitions, ModelHelpers, SoftDeletes;

    protected $table = 'journal_entry';

    protected $fillable = [
        'journal_no',
        'journal_date',
        'status',
        'remarks',
        'posting_period_id',
        'payment_request_id',
        'period_id',
        'entry_date',
        'reference_no',
        'created_by',
    ];

    protected $casts = [
        'journal_date' => 'date:Y-m-d',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(JournalDetails::class);
    }

    public function voucher(): HasMany
    {
        return $this->hasMany(Voucher::class, 'journal_entry_id');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function paymentRequest(): BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class, 'payment_request_id');
    }

    public function scopeWithPaymentRequest($query)
    {
        return $query->with(['paymentRequest.stakeholder', 'paymentRequest.details.particularGroup']);
    }

    public function scopeWithDetails($query)
    {
        return $query->with('details.stakeholder');
    }

    public function scopeWithAccounts($query)
    {
        return $query->with('details.account.accountType', 'details.account.reportGroup');
    }

    public function scopeWithVoucher($query)
    {
        return $query->with(['voucher.details', 'voucher.book']);
    }

    public function scopeWhereVoucherIsApproved($query)
    {
        return $query->whereHas('voucher', function ($query) {
            $query->where('request_status', RequestStatuses::APPROVED->value);
        });
    }

    public function scopeOrderByDesc($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    public function scopeOpenJournals($query)
    {
        return $query->where('status', JournalStatus::OPEN->value);
    }
    public function scopeVoidJournals($query)
    {
        return $query->where('status', JournalStatus::VOID->value);
    }
    public function scopeDraftedJournals($query)
    {
        return $query->where('status', JournalStatus::DRAFTED->value);
    }
    public function scopePostedJournals($query)
    {
        return $query->where('status', JournalStatus::POSTED->value);
    }
    public function scopeUnpostedJournals($query)
    {
        return $query->where('status', JournalStatus::UNPOSTED->value);
    }
    public function scopeForPaymentJournals($query)
    {
        return $query->where('status', JournalStatus::FOR_PAYMENT->value);
    }
    public function scopeForDisbursementJournals($query)
    {
        return $query->where('status', JournalStatus::POSTED->value)->orWhere('status', JournalStatus::UNPOSTED->value);
    }
}
