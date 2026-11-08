<?php

namespace App\Services;

use App\Models\EmergencyReport;
use App\Repositories\EmergencyReportRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EmergencyReportService
{
    public function __construct(
        protected EmergencyReportRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?EmergencyReport
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): EmergencyReport
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): EmergencyReport
    {
        $data['reported_by'] = auth()->id();
        
        return $this->repository->create($data);
    }

    public function update(EmergencyReport $report, array $data): EmergencyReport
    {
        return $this->repository->update($report, $data);
    }

    public function delete(EmergencyReport $report): bool
    {
        return $this->repository->delete($report);
    }

    public function updateStatus(EmergencyReport $report, string $status): EmergencyReport
    {
        $data = ['status' => $status];
        
        if ($status === 'assigned') {
            $data['assigned_at'] = now();
        } elseif ($status === 'resolved') {
            $data['resolved_at'] = now();
        }

        return $this->repository->update($report, $data);
    }

    public function assignResponder(EmergencyReport $report, int $responderId): EmergencyReport
    {
        return $this->repository->update($report, [
            'assigned_responder_id' => $responderId,
            'status' => 'assigned',
            'assigned_at' => now(),
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