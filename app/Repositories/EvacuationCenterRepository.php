<?php

namespace App\Repositories;

use App\Models\EvacuationCenter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EvacuationCenterRepository extends BaseRepository
{
    public function __construct(EvacuationCenter $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['manager', 'affectedPersons']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('address', 'like', "%{$filters['search']}%")
                    ->orWhere('contact_person', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['has_availability'])) {
            $query->where('current_occupancy', '<', 'capacity');
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }

    public function getNearby(float $lat, float $lng, float $radiusKm = 50, int $limit = 10): Collection
    {
        return $this->model->nearby($lat, $lng, $radiusKm)
            ->where('current_occupancy', '<', 'capacity')
            ->orderByRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                 cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))",
                [$lat, $lng, $lat]
            )
            ->limit($limit)
            ->get();
    }

    public function getStatistics(): array
    {
        $total = $this->model->count();
        $byStatus = $this->model->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalCapacity = $this->model->sum('capacity');
        $totalOccupancy = $this->model->sum('current_occupancy');

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'total_capacity' => $totalCapacity,
            'total_occupancy' => $totalOccupancy,
            'available_capacity' => $totalCapacity - $totalOccupancy,
            'occupancy_rate' => $totalCapacity > 0 ? round(($totalOccupancy / $totalCapacity) * 100, 2) : 0,
            'active_count' => $byStatus['active'] ?? 0,
            'full_count' => $byStatus['full'] ?? 0,
        ];
    }
}