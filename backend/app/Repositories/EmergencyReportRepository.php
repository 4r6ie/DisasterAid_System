<?php

namespace App\Repositories;

use App\Models\EmergencyReport;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EmergencyReportRepository extends BaseRepository
{
    public function __construct(EmergencyReport $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['disasterIncident', 'reporter', 'assignedResponder']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('description', 'like', "%{$filters['search']}%")
                    ->orWhere('address', 'like', "%{$filters['search']}%")
                    ->orWhere('contact_name', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['emergency_type'])) {
            $query->where('emergency_type', $filters['emergency_type']);
        }

        if (!empty($filters['disaster_incident_id'])) {
            $query->where('disaster_incident_id', $filters['disaster_incident_id']);
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
        $byPriority = $this->model->selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
        $byType = $this->model->selectRaw('emergency_type, count(*) as count')
            ->groupBy('emergency_type')
            ->pluck('count', 'emergency_type')
            ->toArray();

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_priority' => $byPriority,
            'by_type' => $byType,
            'pending_count' => $byStatus['pending'] ?? 0,
            'critical_count' => $byPriority['critical'] ?? 0,
        ];
    }
}