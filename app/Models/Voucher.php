<?php

namespace App\Models;

use App\Enums\JournalStatus;
use App\Enums\RequestApprovalStatus;
use App\Enums\RequestStatuses;
use App\Enums\TransactionFlowName;
use App\Enums\TransactionFlowStatus;
use App\Enums\VoucherType;
use App\Http\Traits\HasApproval;
use App\Http\Traits\HasTransitions;
use App\Http\Traits\ModelHelpers;
use App\Services\TransactionFlowService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Voucher extends Model
{
    use HasApproval;
    use HasFactory;
    use HasTransitions;
    use ModelHelpers;
    use Notifiable;
    use SoftDeletes;

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
        'attach_file',
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
        return $query->with([
            'details.account',
            'details.stakeholder',
        ]);
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
        return $query->with([
            'journalEntry.paymentRequest.details.stakeholder',
            'journalEntry.paymentRequest.stakeholder',
            'journalEntry.paymentRequest.transactionFlow' => function ($q) {
                $q->orderBy('priority', 'asc')
                    ->orderBy('id', 'asc');
            },
            'journalEntry.details.account.accountType',
            'journalEntry.details.account.reportGroup',
            'journalEntry.details.stakeholder',
        ]);
    }

    public function completeRequestStatus()
    {
        $this->request_status = RequestStatuses::APPROVED->value;
        $this->save();
        $this->refresh();
        if ($this->type === VoucherType::DISBURSEMENT->value) {
            TransactionFlowService::updateTransactionFlow(
                $this->id,
                TransactionFlowName::DISBURSEMENT_VOUCHER_APPROVAL->value,
                TransactionFlowStatus::DONE->value
            );
            $this->journalEntry()->update([
                'status' => JournalStatus::UNPOSTED->value,
            ]);
        } else {
            TransactionFlowService::updateTransactionFlow(
                $this->id,
                TransactionFlowName::CASH_VOUCHER_APPROVALS->value,
                TransactionFlowStatus::DONE->value
            );
            $this->journalEntry()->update([
                'status' => JournalStatus::UNPOSTED->value,
            ]);
        }
    }
    public function denyRequestStatus()
    {
        $this->request_status = RequestStatuses::DENIED->value;
        $this->save();
        $this->refresh();
        if ($this->type === VoucherType::DISBURSEMENT->value) {
            //journal entry
            $this->journalEntry()->update([
                'status' => JournalStatus::VOID->value,
            ]);
            // payment request
            $paymentRequest = $this->journalEntry->paymentRequest;
            $paymentRequest->update([
                'request_status' => RequestApprovalStatus::DENIED,
            ]);
            // voucher request
            TransactionFlowService::updateTransactionFlow(
                $this->id,
                TransactionFlowName::DISBURSEMENT_VOUCHER_APPROVAL->value,
                TransactionFlowStatus::REJECTED->value
            );
        } else {
            // disbursement voucher
            $disbursement = $this->disbursementVoucher;
            $disbursement->update([
                'request_status' => RequestApprovalStatus::DENIED,
            ]);
            // payment request
            $paymentRequest = $this->journalEntry->paymentRequest;
            $paymentRequest->update([
                'request_status' => RequestApprovalStatus::DENIED,
            ]);
            // voucher request
            TransactionFlowService::updateTransactionFlow(
                $this->id,
                TransactionFlowName::CASH_VOUCHER_APPROVALS->value,
                TransactionFlowStatus::REJECTED->value
            );
        }
    }
}
