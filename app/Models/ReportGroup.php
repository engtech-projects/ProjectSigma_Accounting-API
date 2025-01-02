<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportGroup extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "report_groups";
    protected $fillable = [
        'name',
        'description',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
