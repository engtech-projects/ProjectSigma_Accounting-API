<?php

namespace App\Models\Stakeholders;

use App\Models\StakeHolder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'source_id',
        'name'
    ];
    public function stakeholder()
    {
        return $this->morphOne(StakeHolder::class, 'stakeholdable');
    }
}
