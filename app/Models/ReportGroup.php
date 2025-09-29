<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'report_groups';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
    ];

    public function scopeSearchByKey($query, string $key)
    {
        return $query->where(function ($q) use ($key) {
            $q->where('name', 'like', '%' . $key . '%')
                ->orWhere('description', 'like', '%' . $key . '%');
        });
    }

    public function scopeSearchByName($query, string $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeSearchByDescription($query, string $description)
    {
        return $query->where('description', 'like', '%' . $description . '%');
    }

    public function scopeFilter($query, array $filters = [])
    {
        if (!empty($filters['key'])) {
            $query->searchByKey($filters['key']);
        }
        if (!empty($filters['name'])) {
            $query->searchByName($filters['name']);
        }
        if (!empty($filters['description'])) {
            $query->searchByDescription($filters['description']);
        }
        return $query;
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
