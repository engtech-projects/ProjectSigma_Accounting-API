<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Activity extends Model
{
    use HasFactory;

    protected $table = "activites";
    protected $primaryKey = "activity_id";
    protected $fillable = [
        "act_type_id",
        "action",
        "action_by",
        "model",
        "activity_date",
    ];




    public static function createActivity($attributes)
    {
        return DB::transaction(function () use ($attributes) {
            return parent::create($attributes);
        });
    }
}
