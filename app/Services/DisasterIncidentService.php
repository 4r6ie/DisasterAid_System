<?php

namespace App\Services;

use App\Models\DisasterIncident;
use App\Repositories\DisasterIncidentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DisasterIncidentService
{
    public function __construct(
        protected DisasterIncidentRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?DisasterIncident
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): DisasterIncident
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): DisasterIncident
    {
        $data['incident_id'] = $this->generateIncidentId();
        $data['reported_by'] = auth()->id();
        
        return $this->repository->create($data);
    }

    public function update(DisasterIncident $incident, array $data): DisasterIncident
    {
        return $this->repository->update($incident, $data);
    }

    public function delete(DisasterIncident $incident): bool
    {
        return $this->repository->delete($incident);
    }

    public function updateStatus(DisasterIncident $incident, string $status): DisasterIncident
    {
        $data = ['status' => $status];
        
        if ($status === 'resolved') {
            $data['resolved_at'] = now();
        }

        return $this->repository->update($incident, $data);
    }

    public function updateCasualties(DisasterIncident $incident, array $casualties): DisasterIncident
    {
        return $this->repository->update($incident, $casualties);
    }

    public function assignResponder(DisasterIncident $incident, int $responderId): DisasterIncident
    {
        return $this->repository->update($incident, ['assigned_responder_id' => $responderId]);
    }

    public function getNearby(float $lat, float $lng, float $radiusKm = 50, int $limit = 10): Collection
    {
        return $this->repository->getNearby($lat, $lng, $radiusKm, $limit);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }

    public function getTrends(int $days = 30): array
    {
        return $this->repository->getTrends($days);
    }

    protected function generateIncidentId(): string
    {
        $year = now()->year;
        $lastIncident = DisasterIncident::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastIncident && preg_match('/DIS-' . $year . '-(\d+)/', $lastIncident->incident_id, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }

        return sprintf('DIS-%d-%03d', $year, $sequence);
    }
}