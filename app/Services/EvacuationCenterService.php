<?php

namespace App\Services;

use App\Models\EvacuationCenter;
use App\Repositories\EvacuationCenterRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EvacuationCenterService
{
    public function __construct(
        protected EvacuationCenterRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?EvacuationCenter
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): EvacuationCenter
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): EvacuationCenter
    {
        return $this->repository->create($data);
    }

    public function update(EvacuationCenter $center, array $data): EvacuationCenter
    {
        return $this->repository->update($center, $data);
    }

    public function delete(EvacuationCenter $center): bool
    {
        return $this->repository->delete($center);
    }

    public function updateOccupancy(EvacuationCenter $center, int $occupancy): EvacuationCenter
    {
        $occupancy = max(0, min($occupancy, $center->capacity));
        
        $status = $center->status;
        if ($occupancy >= $center->capacity) {
            $status = 'full';
        } elseif ($occupancy === 0) {
            $status = 'active';
        }

        return $this->repository->update($center, [
            'current_occupancy' => $occupancy,
            'status' => $status,
        ]);
    }

    public function getNearby(float $lat, float $lng, float $radiusKm = 50, int $limit = 10): Collection
    {
        return $this->repository->getNearby($lat, $lng, $radiusKm, $limit);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }
}