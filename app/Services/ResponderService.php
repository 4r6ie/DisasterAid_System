<?php

namespace App\Services;

use App\Models\Responder;
use App\Repositories\ResponderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponderService
{
    public function __construct(
        protected ResponderRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?Responder
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): Responder
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Responder
    {
        return $this->repository->create($data);
    }

    public function update(Responder $responder, array $data): Responder
    {
        return $this->repository->update($responder, $data);
    }

    public function delete(Responder $responder): bool
    {
        return $this->repository->delete($responder);
    }

    public function updateStatus(Responder $responder, string $status): Responder
    {
        return $this->repository->update($responder, ['status' => $status]);
    }

    public function getAvailable(string $specialization = '', int $limit = 50): Collection
    {
        return $this->repository->getAvailable($specialization, $limit);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }
}