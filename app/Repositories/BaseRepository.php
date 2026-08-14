<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->get($columns);
    }

    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    public function findOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function forceDelete(Model $model): bool
    {
        return $model->forceDelete();
    }

    public function restore(Model $model): bool
    {
        return $model->restore();
    }

    public function paginate(
        int $perPage = 15,
        array $columns = ['*'],
        string $pageName = 'page',
        ?int $page = null
    ): LengthAwarePaginator {
        return $this->model->paginate($perPage, $columns, $pageName, $page);
    }

    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function with(array|string $relations): self
    {
        $this->model = $this->model->with($relations);
        return $this;
    }

    public function where(string $column, mixed $operator = null, mixed $value = null): self
    {
        $this->model = $this->model->where($column, $operator, $value);
        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        $this->model = $this->model->whereIn($column, $values);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->model = $this->model->orderBy($column, $direction);
        return $this;
    }

    public function latest(string $column = 'created_at'): self
    {
        $this->model = $this->model->latest($column);
        return $this;
    }

    public function oldest(string $column = 'created_at'): self
    {
        $this->model = $this->model->oldest($column);
        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function resetQuery(): self
    {
        $this->model = $this->model->newQuery();
        return $this;
    }
}