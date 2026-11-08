<?php

namespace App\Repositories;

use App\Models\VolunteerAssignment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VolunteerAssignmentRepository extends BaseRepository
{
    public function __construct(VolunteerAssignment $model)
    {
        parent::__construct($model);
    }

    public function getFiltered(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with(['volunteer', 'disasterIncident', 'assigner']);

        if (!empty($filters['volunteer_id'])) {
            $query->where('volunteer_id', $filters['volunteer_id']);
        }

        if (!empty($filters['disaster_incident_id'])) {
            $query->where('disaster_incident_id', $filters['disaster_incident_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('assigned_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('assigned_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['sort_by'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir'] ?? 'desc');
        } else {
            $query->latest('assigned_at');
        }

        return $query->paginate($perPage);
    }

    public function getByVolunteer(int $volunteerId, int $limit = 50): Collection
    {
        return $this->model->where('volunteer_id', $volunteerId)
            ->latest('assigned_at')
            ->limit($limit)
            ->get();
    }

    public function getByDisasterIncident(int $disasterIncidentId, int $limit = 50): Collection
    {
        return $this->model->where('disaster_incident_id', $disasterIncidentId)
            ->latest('assigned_at')
            ->limit($limit)
            ->get();
    }
}