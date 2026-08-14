<?php

namespace App\Services;

use App\Models\Announcement;
use App\Repositories\AnnouncementRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AnnouncementService
{
    public function __construct(
        protected AnnouncementRepository $repository
    ) {}

    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getFiltered($filters, $perPage);
    }

    public function getById(int $id): ?Announcement
    {
        return $this->repository->find($id);
    }

    public function getByIdOrFail(int $id): Announcement
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $data): Announcement
    {
        $data['created_by'] = auth()->id();
        
        return $this->repository->create($data);
    }

    public function update(Announcement $announcement, array $data): Announcement
    {
        return $this->repository->update($announcement, $data);
    }

    public function delete(Announcement $announcement): bool
    {
        return $this->repository->delete($announcement);
    }

    public function publish(Announcement $announcement): Announcement
    {
        return $this->repository->update($announcement, [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublish(Announcement $announcement): Announcement
    {
        return $this->repository->update($announcement, [
            'is_published' => false,
        ]);
    }

    public function getActive(int $limit = 50): Collection
    {
        return $this->repository->getActive($limit);
    }

    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }
}