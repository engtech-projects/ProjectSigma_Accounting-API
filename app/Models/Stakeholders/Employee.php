<?php

namespace App\Models\Stakeholders;

use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ModelHelpers;

    protected $fillable = [
        'source_id',
        'name',
        'first_name',
        'middle_name',
        'family_name',
    ];
    protected $appends = [
        'fullname_last',
        'fullname_first',
    ];
    protected $casts = [
        'date_of_birth' => 'datetime',
        'date_of_marriage' => 'datetime',
    ];

    /**
    * ==================================================
    * MODEL ATTRIBUTES
    * ==================================================
    */
    public function getAgeAttribute()
    {
        if (!$this->date_of_birth) {
            return "Date of birth not set.";
        }
        return $this->date_of_birth->age;
    }
    public function getFullnameLastAttribute()
    {
        return $this->family_name . ", " . implode(
            " ",
            array_values(array_filter([
                $this->first_name,
                $this->middle_name,
                ]))
        );
    }

    public function getFullnameFirstAttribute()
    {
        return implode(
            " ",
            array_values(
                array_filter([
                    $this->first_name,
                    $this->middle_name,
                    $this->family_name,
                ])
            )
        );
    }
    public function getFullnameLastMiAttribute()
    {
        return $this->family_name . ", " . implode(
            " ",
            array_values(array_filter([ // To remove null values
                $this->first_name,
                $this->middle_initial,
            ]))
        );
    }
    public function getFullnameFirstMiAttribute()
    {
        return implode(
            " ",
            array_values(
                array_filter([
                    $this->first_name,
                    $this->middle_initial,
                    $this->family_name,
                ])
            )
        );
    }
    public function getMiddleInitialAttribute()
    {
        return $this->middle_name ? substr($this->middle_name, 0, 1) . "." : null;
    }
}
