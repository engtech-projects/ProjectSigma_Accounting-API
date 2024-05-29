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

    protected $fillable = [
        'transaction_no',
        'transaction_date',
        'status',
        'reference_no',
        'transaction_type_id',
        'period_id',
        'stakeholder_id',
        'description',
        'note',
        'amount'
    ];

    protected $primaryKey = "transaction_id";
    protected $table = "transactions";


    public function stakeholder(): BelongsTo
    {
        return $this->belongsTo(StakeHolder::class, 'stakeholder_id', 'stakeholder_id');
    }
    public function transaction_details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, "transaction_id", "transaction_id");
    }
    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
