<?php

namespace App\Models;

use App\Enums\VoucherType;
use App\Http\Traits\HasApproval;
use App\Http\Traits\HasTransitions;
use App\Http\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Voucher extends Model
{
    use HasApproval, HasFactory, HasTransitions, ModelHelpers, Notifiable, SoftDeletes;

    protected $table = 'voucher';

    protected $fillable = [
        'check_no',
        'voucher_no',
        'stakeholder_id',
        'particulars',
        'net_amount',
        'amount_in_words',
        'journal_entry_id',
        'type',
        'voucher_date',
        'date_encoded',
        'book_id',
        'request_status',
        'reference_no',
        'approvals',
        'created_by',
        'received_by',
        'received_date',
        'receipt_no',
        'attach_file'
    ];

    protected $casts = [
        'date_encoded' => 'date:Y-m-d',
        'voucher_date' => 'date:Y-m-d',
        'approvals' => 'array',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(VoucherDetails::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class);
    }

    public function scopeFilterBook($query, $bookId)
    {
        return $query->where('book_id', $bookId);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWhereCash($query)
    {
        return $query->where('type', VoucherType::CASH->value);
    }

    public function scopeWhereDisbursement($query)
    {
        return $query->where('type', VoucherType::DISBURSEMENT->value);
    }

    public function scopeOrderDesc($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['details.account']);
    }

    public function scopeClearedVoucherCash($query)
    {
        return $query->whereNotNull('received_by')->whereNotNull('received_date');
    }

    public function scopeUnclearedVoucherCash($query)
    {
        return $query->whereNull('received_by')->whereNull('received_date');
    }

    public function scopeWithPaymentRequestDetails($query)
    {
        return $query->with(['journalEntry.paymentRequest.details.stakeholder', 'journalEntry.paymentRequest.stakeholder']);
    }
}
