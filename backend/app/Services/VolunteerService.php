<?php

namespace App\Services;

use App\Models\Volunteer;
use App\Repositories\VolunteerRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VolunteerService
{
    public function __construct(
        protected VolunteerRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?Volunteer
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): Volunteer
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Volunteer
    {
        return $this->repository->create($data);
    }

    public function update(Volunteer $volunteer, array $data): Volunteer
    {
        return $this->repository->update($volunteer, $data);
    }

    public function delete(Volunteer $volunteer): bool
    {
        return $this->repository->delete($volunteer);
    }

    public function updateStatus(Volunteer $volunteer, string $status): Volunteer
    {
        return $this->repository->update($volunteer, ['status' => $status]);
    }

    public function getAvailable(array $skills = [], int $limit = 50): Collection
    {
        return $this->repository->getAvailable($skills, $limit);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }
}