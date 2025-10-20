<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubGroup extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sub_groups';

    protected $fillable = [
        'name',
        'description',
    ];

    public function scopeSearchByKey($query, string $key): void
    {
        $query->where(function ($q) use ($key) {
            $q->where('name', 'like', '%' . $key . '%')
                ->orWhere('description', 'like', '%' . $key . '%');
        });
    }

    public function scopeSearchByName($query, string $name): void
    {
        $query->where('name', 'like', '%' . $name . '%');
    }

    public function scopeSearchByDescription($query, string $description): void
    {
        $query->where('description', 'like', '%' . $description . '%');
    }

    public function scopeFilter($query, array $filters = []): void
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
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
