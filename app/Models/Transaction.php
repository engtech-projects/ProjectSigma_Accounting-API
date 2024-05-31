<?php

namespace App\Models;

use App\Models\Pivot\TransactionDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
            $model->transaction_no = $model->generateTransactionNumber();
            $model->reference_no = $model->generateReferenceNumber();
            $model->period_id = 1;
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
    public function generateTransactionNumber()
    {
        return rand(5, 100);
    }
    public function generateReferenceNumber()
    {
        return rand(5, 100);
    }
    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
