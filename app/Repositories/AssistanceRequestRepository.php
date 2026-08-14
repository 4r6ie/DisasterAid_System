<?php

namespace App\Repositories;

use App\Models\AssistanceRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AssistanceRequestRepository extends BaseRepository
{
    public function __construct(AssistanceRequest $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['disasterIncident', 'requester', 'approver', 'dispatcher']);

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('request_number', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%")
                    ->orWhere('address', 'like', "%{$filters['search']}%")
                    ->orWhere('contact_name', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['urgency'])) {
            $query->where('urgency', $filters['urgency']);
        }

        if (!empty($filters['assistance_type'])) {
            $query->where('assistance_type', $filters['assistance_type']);
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

    public function getStatistics(): array
    {
        $total = $this->model->count();
        $byStatus = $this->model->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        $byUrgency = $this->model->selectRaw('urgency, count(*) as count')
            ->groupBy('urgency')
            ->pluck('count', 'urgency')
            ->toArray();
        $byType = $this->model->selectRaw('assistance_type, count(*) as count')
            ->groupBy('assistance_type')
            ->pluck('count', 'assistance_type')
            ->toArray();

        $totalQuantity = $this->model->sum('quantity');

        return [
            'total' => $total,
            'by_status' => $byStatus,
            'by_urgency' => $byUrgency,
            'by_type' => $byType,
            'total_quantity' => $totalQuantity,
            'pending_count' => $byStatus['pending'] ?? 0,
            'critical_count' => $byUrgency['critical'] ?? 0,
        ];
    }
}