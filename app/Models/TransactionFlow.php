<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFlow extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'transaction_flow';
    protected $fillable = [
        'id',
        'payment_request_id',
        'unique_name',
        'name',
        'description',
        'category',
        'status',
        'priority',
    ];

    public function paymentRequest(): BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class);
    }
}
