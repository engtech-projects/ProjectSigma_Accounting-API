<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFlow extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'transaction_flow';

    public $timestamps = true;

    protected $fillable = [
        'payment_request_id',
        'unique_name',
        'name',
        'user_id',
        'user_name',
        'description',
        'category',
        'status',
        'priority',
        'is_passable',
        'is_assignable',
    ];

    public function paymentRequest(): BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class);
    }
}
