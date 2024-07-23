<?php

namespace App\Models\Pivot;

use App\Models\StakeHolder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StakeHolderTypeGroup extends Pivot
{
    //
    public function stakeholders(): HasMany
    {
        return $this->hasMany(StakeHolder::class, 'stakeholder_type_id');
    }
}
