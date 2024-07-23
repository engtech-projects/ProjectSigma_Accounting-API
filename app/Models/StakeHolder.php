<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = [
        "fullname_first",
        "fullname_last"
    ];


    protected function fullnameLast(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstname . ", " . $this->lastname . " " . $this->middlename
                . " " . $this->suffix,
        );
    }
    protected function fullnameFirst(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->firstname . " " . $this->middlename . " " . $this->lastname
                . " " . $this->suffix,
        );
    }
}
