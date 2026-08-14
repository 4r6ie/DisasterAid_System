<?php

namespace App\Repositories;

use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VolunteerRepository extends BaseRepository
{
    public function __construct(Volunteer $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('assignments');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%")
                    ->orWhere('phone', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['skill'])) {
            $query->whereJsonContains('skills', $filters['skill']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('joined_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('joined_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest('joined_at');
        }

        return $query->paginate($perPage);
    }

    public function getAvailable(array $skills = [], int $limit = 50): Collection
    {
        $query = $this->model->where('status', 'available');

        if (!empty($skills)) {
            foreach ($skills as $skill) {
                $query->whereJsonContains('skills', $skill);
            }
        }

        return $query->limit($limit)->get();
    }

    public function getStatistics(): array
    {
        $total = $this->model->count();
        $byStatus = $this->model->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $allSkills = $this->model->pluck('skills')->flatten()->unique()->values()->toArray();
        $skillCounts = [];
        foreach ($allSkills as $skill) {
            $skillCounts[$skill] = $this->model->whereJsonContains('skills', $skill)->count();
        }

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'skills' => $skillCounts,
            'available_count' => $byStatus['available'] ?? 0,
        ];
    }
}