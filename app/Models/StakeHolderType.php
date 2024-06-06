<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StakeHolderType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "stakeholder_types";
    protected $primaryKey = "stakeholder_type_id";
    protected $fillable = [
        'stakeholder_type_name'
    ];

    public function stakeholders(): HasMany
    {
        return $this->hasMany(StakeHolder::class, 'stakeholder_type_id', 'stakeholder_type_id');
    }
}
