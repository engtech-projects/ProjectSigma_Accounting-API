<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subsidiary extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "subsidiaries";
    protected $primaryKey = "subsidiary_id";

    protected $fillable = [
        "subsidiary_name"
    ];


    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
