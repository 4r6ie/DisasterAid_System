<?php

namespace App\Services;

use App\Models\VolunteerAssignment;
use App\Repositories\VolunteerAssignmentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VolunteerAssignmentService
{
    public function __construct(
        protected VolunteerAssignmentRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?VolunteerAssignment
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): VolunteerAssignment
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): VolunteerAssignment
    {
        $data['assigned_by'] = auth()->id();
        $data['assigned_at'] = now();
        
        return $this->repository->create($data);
    }

    public function update(VolunteerAssignment $assignment, array $data): VolunteerAssignment
    {
        return $this->repository->update($assignment, $data);
    }

    public function delete(VolunteerAssignment $assignment): bool
    {
        return $this->repository->delete($assignment);
    }

    public function getByVolunteer(int $volunteerId, int $limit = 50): Collection
    {
        return $this->repository->getByVolunteer($volunteerId, $limit);
    }

    public function getByDisasterIncident(int $disasterIncidentId, int $limit = 50): Collection
    {
        return $this->repository->getByDisasterIncident($disasterIncidentId, $limit);
    }
}