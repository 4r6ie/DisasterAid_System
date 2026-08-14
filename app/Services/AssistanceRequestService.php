<?php

namespace App\Services;

use App\Models\AssistanceRequest;
use App\Repositories\AssistanceRequestRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AssistanceRequestService
{
    public function __construct(
        protected AssistanceRequestRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?AssistanceRequest
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): AssistanceRequest
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): AssistanceRequest
    {
        $data['request_number'] = $this->generateRequestNumber();
        $data['requested_by'] = auth()->id();
        
        return $this->repository->create($data);
    }

    public function update(AssistanceRequest $request, array $data): AssistanceRequest
    {
        return $this->repository->update($request, $data);
    }

    public function delete(AssistanceRequest $request): bool
    {
        return $this->repository->delete($request);
    }

    public function approve(AssistanceRequest $request): AssistanceRequest
    {
        return $this->repository->update($request, [
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }

    public function dispatch(AssistanceRequest $request): AssistanceRequest
    {
        return $this->repository->update($request, [
            'status' => 'dispatched',
            'dispatched_by' => auth()->id(),
            'dispatched_at' => now(),
        ]);
    }

    public function complete(AssistanceRequest $request): AssistanceRequest
    {
        return $this->repository->update($request, [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function reject(AssistanceRequest $request, string $reason): AssistanceRequest
    {
        return $this->repository->update($request, [
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }

    protected function generateRequestNumber(): string
    {
        $year = now()->year;
        $lastRequest = AssistanceRequest::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = 1;
        if ($lastRequest && preg_match('/REQ-' . $year . '-(\d+)/', $lastRequest->request_number, $matches)) {
            $sequence = (int)$matches[1] + 1;
        }

        return sprintf('REQ-%d-%04d', $year, $sequence);
    }
}