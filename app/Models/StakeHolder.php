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

    public static function findIdByIdOrNull(?int $id): ?int
    {
        if (! $id) {
            return null;
        }
        $stakeholder = self::where('id', $id)->first();

        return $stakeholder?->id;
    }

    public static function findNameByIdOrNull(?int $id): ?string
    {
        $stakeholder = self::where('id', $id)->first();

        return $stakeholder?->name;
    }

    public static function findStakeholderByNameOrNull(?string $id): ?StakeHolder
    {
        $stakeholder = self::where('id', $id)->first();

        return $stakeholder;
    }

    public static function findIdByNameOrNull(?string $name): ?int
    {
        $stakeholder = self::where('name', $name)->first();

        return $stakeholder?->id;
    }
}
