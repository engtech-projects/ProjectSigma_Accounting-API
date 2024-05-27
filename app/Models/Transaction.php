<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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


    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
