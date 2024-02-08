<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentSeries extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "document_series";

    protected $primaryKey = "series_id";

    protected $fillable = [
        "series_scheme",
        "series_description",
        "next_number",
        "status",
        "transaction_type_id"
    ];

    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
