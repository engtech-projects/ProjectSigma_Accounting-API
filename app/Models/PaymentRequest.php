<?php

namespace App\Models;

use App\Enums\PaymentRequestType;
use App\Enums\RequestStatuses;
use App\Enums\TransactionFlowName;
use App\Enums\TransactionFlowStatus;
use App\Http\Traits\HasApproval;
use App\Http\Traits\ModelHelpers;
use App\Services\TransactionFlowService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class PaymentRequest extends Model
{
    use HasApproval;
    use HasFactory;
    use ModelHelpers;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'payment_request';

    protected $fillable = [
        'stakeholder_id',
        'prf_no',
        'request_date',
        'description',
        'total',
        'approvals',
        'type',
        'created_by',
        'request_status',
        'total_vat_amount',
        'attachment_url',
    ];

    protected $casts = [
        'approvals' => 'array',
    ];

    protected $appends = [
        'taxableAmount',
    ];

    public function transactionFlow(): HasMany
    {
        return $this->hasMany(TransactionFlow::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(PaymentRequestDetails::class);
    }

    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function particularGroup(): BelongsTo
    {
        return $this->belongsTo(ParticularGroup::class);
    }

    public function scopePrfNo($query, $prfNo)
    {
        return $query->where('prf_no', $prfNo);
    }

    public function scopeWithPaymentRequestDetails($query)
    {
        return $query->with(['details.stakeholder']);
    }

    public function scopeFormStatus($query, $status)
    {
        return $query->whereHas('form', function ($query) use ($status) {
            $query->where('forms.status', $status);
        });
    }

    public function scopeWithDetails($query)
    {
        return $query->with(['details.stakeholder']);
    }

    public function scopeOrderByDesc($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeWithCreatedBy($query)
    {
        return $query->with('created_by_user');
    }

    public function scopeWithNoReferenceNo($query)
    {
        return $query->whereDoesntHave('vouchers');
    }

    public function vouchers(): HasOne
    {
        return $this->HasOne(Voucher::class, 'prf_no', 'reference_no');
    }

    public function journalEntry(): HasMany
    {
        return $this->hasMany(JournalEntry::class, 'payment_request_id');
    }

    public function withHoldingTax(): BelongsTo
    {
        return $this->BelongsTo(WithHoldingTax::class);
    }

    public function scopePayroll($query)
    {
        return $query->where('type', PaymentRequestType::PAYROLL->value);
    }

    public function scopePayment($query)
    {
        return $query->where('type', PaymentRequestType::PRF->value);
    }

    public function scopeWithJournalEntry($query)
    {
        return $query->with('journalEntry');
    }

    public function scopeWithHoldingTax($query)
    {
        return $query->with('withHoldingTax');
    }

    public function scopeWithJournalEntryVouchers($query)
    {
        return $query->with('journalEntry.voucher');
    }

    public function scopeWithTransactionFlow($query)
    {
        return $query->with(['transactionFlow' => function ($query) {
            $query->orderBy('priority', 'asc');
        }]);
    }

    public function scopeMyDeniedRequest($query)
    {
        return $query->where('request_status', RequestStatuses::DENIED->value)
            ->where('created_by', auth()->user()->id);
    }

    public function getTaxableAmountAttribute()
    {
        return floatval($this->total) - floatval($this->total_vat_amount);
    }
    public function completeRequestStatus()
    {
        $this->request_status = RequestStatuses::APPROVED->value;
        $this->save();
        $this->refresh();
        TransactionFlowService::updateTransactionFlow(
            $this->id,
            TransactionFlowName::PRF_APPROVAL->value,
            TransactionFlowStatus::DONE->value
        );
    }
}
