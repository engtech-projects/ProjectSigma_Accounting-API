<?php

namespace App\Services;

use App\Models\PostingPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PostingPeriodService
{
    public static function getPaginated(array $filters = [])
    {
        $query = PostingPeriod::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }
        return $query->orderBy('created_at', 'desc')->paginate(config('services.pagination.limit'));
    }

    public static function create(array $data): PostingPeriod
    {
        return PostingPeriod::create($data);
    }

    public static function update(PostingPeriod $postingPeriod, array $data): bool
    {
        return $postingPeriod->update($data);
    }

    public static function delete(PostingPeriod $postingPeriod): bool
    {
        return $postingPeriod->delete();
    }

    public static function findById(int $id): ?PostingPeriod
    {
        return PostingPeriod::find($id);
    }
}
