<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionType extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = "transaction_types";
    protected $primaryKey = "transaction_type_id";

    protected $fillable = [
        "transaction_type_name",
        "book_id",
        "account_id"
    ];


    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
