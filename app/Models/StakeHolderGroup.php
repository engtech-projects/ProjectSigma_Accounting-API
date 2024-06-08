<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StakeHolderGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stakeholder_groups';
    protected $primaryKey = 'stakeholder_group_id';

    protected $fillable = [
        'stakeholder_group_name',
    ];

    public function type_groups(): BelongsToMany
    {
        return $this->belongsToMany(StakeHolderType::class, 'stakeholder_type_groups', 'stakeholder_group_id', 'stakeholder_type_id')
            ->withPivot(['stakeholder_group_id', 'stakeholder_type_id'])
            ->withTimestamps();
    }


    public function type_groups_stakeholders(): BelongsToMany
    {
        return $this->belongsToMany(StakeHolder::class, 'stakeholder_type_groups', 'stakeholder_group_id', 'stakeholder_type_id')
            ->withPivot(['stakeholder_group_id', 'stakeholder_type_id'])
            ->withTimestamps();
    }
}
