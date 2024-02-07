<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentSeries extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "document_series";

    protected $primaryKey = "document_series_id";

    protected $fillable = [
        "document_type",
        "scheme",
        "description",
        "next_number",
        "status"
    ];
}
