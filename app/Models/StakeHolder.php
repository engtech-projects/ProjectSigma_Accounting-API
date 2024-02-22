<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StakeHolder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "stakeholders";
    protected $primaryKey = "stakeholder_id";
    protected $fillable = [
        "title",
        "firstname",
        "middlename",
        "lastname",
        "suffix",
        "email",
        "company",
        "display_name",
        "street",
        "city",
        "state",
        "country",
        "phone_number",
        "mobile_number",
        "stakeholder_type_id"
    ];
}
