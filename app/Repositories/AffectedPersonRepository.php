<?php

namespace App\Repositories;

use App\Models\AffectedPerson;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AffectedPersonRepository extends BaseRepository
{
    public function __construct(AffectedPerson $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['disasterIncident', 'evacuationCenter', 'registrar']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('contact_phone', 'like', "%{$filters['search']}%")
                    ->orWhere('family_contact_name', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['disaster_incident_id'])) {
            $query->where('disaster_incident_id', $filters['disaster_incident_id']);
        }

        if (!empty($filters['condition'])) {
            $query->where('condition', $filters['condition']);
        }

        if (!empty($filters['evacuation_center_id'])) {
            $query->where('evacuation_center_id', $filters['evacuation_center_id']);
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

    public function getByDisasterIncident(int $disasterIncidentId, int $limit = 50): Collection
    {
        return $this->model->where('disaster_incident_id', $disasterIncidentId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getByEvacuationCenter(int $evacuationCenterId, int $limit = 50): Collection
    {
        return $this->model->where('evacuation_center_id', $evacuationCenterId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getStatistics(): array
    {
        $total = $this->model->count();
        $byCondition = $this->model->selectRaw('condition, count(*) as count')
            ->groupBy('condition')
            ->pluck('count', 'condition')
            ->toArray();

        $totalInEvacuation = $this->model->whereNotNull('evacuation_center_id')->count();

        return [
            'total' => $total,
            'by_condition' => $byCondition,
            'in_evacuation' => $totalInEvacuation,
            'safe_count' => $byCondition['safe'] ?? 0,
            'injured_count' => $byCondition['injured'] ?? 0,
            'critical_count' => $byCondition['critical'] ?? 0,
            'missing_count' => $byCondition['missing'] ?? 0,
            'deceased_count' => $byCondition['deceased'] ?? 0,
            'rescued_count' => $byCondition['rescued'] ?? 0,
        ];
    }
}