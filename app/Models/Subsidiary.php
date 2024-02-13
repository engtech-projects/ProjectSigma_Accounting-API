<?php

namespace App\Models;

use App\Observers\SubsidiaryObserver;
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


    /* public static function boot()
    {
        parent::boot();
        Subsidiary::observe(SubsidiaryObserver::class);
    } */

    ### MODEL SCOPE BINDINGS ###

    /** LOCAL SCOPES */

    /** DYNAMIC SCOPES */
}
