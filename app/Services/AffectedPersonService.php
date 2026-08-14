<?php

namespace App\Services;

use App\Models\AffectedPerson;
use App\Repositories\AffectedPersonRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AffectedPersonService
{
    public function __construct(
        protected AffectedPersonRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?AffectedPerson
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): AffectedPerson
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): AffectedPerson
    {
        $data['registered_by'] = auth()->id();
        
        return $this->repository->create($data);
    }

    public function update(AffectedPerson $person, array $data): AffectedPerson
    {
        return $this->repository->update($person, $data);
    }

    public function delete(AffectedPerson $person): bool
    {
        return $this->repository->delete($person);
    }

    public function updateCondition(AffectedPerson $person, string $condition): AffectedPerson
    {
        return $this->repository->update($person, ['condition' => $condition]);
    }

    public function assignEvacuationCenter(AffectedPerson $person, int $centerId): AffectedPerson
    {
        return $this->repository->update($person, [
            'evacuation_center_id' => $centerId,
            'evacuated_at' => now(),
        ]);
    }

    public function getByDisasterIncident(int $disasterIncidentId, int $limit = 50): Collection
    {
        return $this->repository->getByDisasterIncident($disasterIncidentId, $limit);
    }

    public function getByEvacuationCenter(int $evacuationCenterId, int $limit = 50): Collection
    {
        return $this->repository->getByEvacuationCenter($evacuationCenterId, $limit);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }
}