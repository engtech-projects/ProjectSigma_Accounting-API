<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakeHolder extends Model
{
    use HasFactory;

    protected $table = 'stakeholder';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'stakeholdable_type',
        'stakeholdable_id',
    ];

    public function stakeholdable()
    {
        return $this->morphTo();
    }
}
