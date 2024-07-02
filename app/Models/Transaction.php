<?php

namespace App\Models;

use App\Exceptions\DBTransactionException;
use App\Exceptions\ResourceNotFound;
use App\Models\Pivot\TransactionDetail;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "transaction_id";
    protected $table = "transactions";
    protected $fillable = [
        'transaction_date',
        'status',
        'reference_no',
        'transaction_type_id',
        'stakeholder_id',
        'description',
        'note',
        'amount',
    ];




    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            
            $model->created_by = auth()->user()->id;
            $model->transaction_no = $model->generateTransactionNumber($model->transaction_type_id);
            $model->reference_no = $model->generateReferenceNumber();

            $model->period_id = PostingPeriod::open_status()->period_id;
        });
    }

    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class, 'stakeholder_id', 'stakeholder_id');
    }
    public function transaction_details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, "transaction_id", "transaction_id");
    }
    public function transaction_type(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'transaction_type_id');
    }
    public function posting_period()
    {
        return $this->belongsTo(PostingPeriod::class, 'period_id', 'period_id');
    }
    public function generateTransactionNumber($transactionTypeId)
    {
        try {
            $transactionType = TransactionType::whereHas('document_series', function ($query) use ($transactionTypeId) {
                $query->where('transaction_type_id', $transactionTypeId);
            })->firstOrFail();
            $series = $transactionType->document_series->activeSeries()->first();
            $transactionNo = $series->series_scheme . $series->next_number;
            $series->next_number = $series->next_number + 1;
            $series->save();
            return $transactionNo;
        } catch (Exception $e) {
            throw new ResourceNotFound("Unable to generate transaction number. Transaction type doesn't have document series.", 422, $e);
        }
    }
    public function generateReferenceNumber()
    {
        return rand(5, 100);
    }
    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
