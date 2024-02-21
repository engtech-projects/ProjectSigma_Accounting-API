<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StakeHolderGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stakeholder_groups';
    protected $primaryKey = 'stakeholder_group_id';

    protected $fillable = [
        'stakeholder_group_name'
    ];
}
