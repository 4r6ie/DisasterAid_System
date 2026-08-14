<?php

namespace App\Repositories;

use App\Models\DisasterIncident;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DisasterIncidentRepository extends BaseRepository
{
    public function __construct(DisasterIncident $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                    ->orWhere('incident_id', 'like', "%{$filters['search']}%")
                    ->orWhere('address', 'like', "%{$filters['search']}%")
                    ->orWhere('city', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['severity'])) {
            $query->where('severity', $filters['severity']);
        }

        if (!empty($filters['disaster_type'])) {
            $query->where('disaster_type', $filters['disaster_type']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('occurred_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('occurred_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest('occurred_at');
        }

        return $query->paginate($perPage);
    }

    public function getNearby(float $lat, float $lng, float $radiusKm = 50, int $limit = 10): Collection
    {
        return $this->model->nearby($lat, $lng, $radiusKm)
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
        $bySeverity = $this->model->selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();
        $byType = $this->model->selectRaw('disaster_type, count(*) as count')
            ->groupBy('disaster_type')
            ->pluck('count', 'disaster_type')
            ->toArray();

        $totalCasualties = $this->model->sum('affected_count') +
            $this->model->sum('injured_count') +
            $this->model->sum('missing_count') +
            $this->model->sum('deceased_count');

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_severity' => $bySeverity,
            'by_type' => $byType,
            'total_casualties' => $totalCasualties,
            'active_count' => $byStatus['active'] ?? 0,
            'critical_count' => $byStatus['critical'] ?? 0,
        ];
    }

    public function getTrends(int $days = 30): array
    {
        $startDate = now()->subDays($days);
        
        return $this->model
            ->where('occurred_at', '>=', $startDate)
            ->selectRaw('DATE(occurred_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->date => $item->count])
            ->toArray();
    }
}