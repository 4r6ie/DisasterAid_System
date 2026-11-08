<?php

namespace App\Repositories;

use App\Models\Responder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponderRepository extends BaseRepository
{
    public function __construct(Responder $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('user');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('employee_id', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%")
                    ->orWhere('specialization', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['specialization'])) {
            $query->where('specialization', $filters['specialization']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('hired_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('hired_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest('hired_at');
        }

        return $query->paginate($perPage);
    }

    public function getAvailable(string $specialization = '', int $limit = 50): Collection
    {
        $query = $this->model->where('status', 'available');

        if ($specialization) {
            $query->where('specialization', $specialization);
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
        $bySpecialization = $this->model->selectRaw('specialization, count(*) as count')
            ->groupBy('specialization')
            ->pluck('count', 'specialization')
            ->toArray();

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_specialization' => $bySpecialization,
            'available_count' => $byStatus['available'] ?? 0,
            'on_duty_count' => $byStatus['on_duty'] ?? 0,
        ];
    }
}