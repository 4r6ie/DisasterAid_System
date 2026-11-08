---
tags: [code, backend, backend-repository]
---

# BaseRepository

`backend/app/Repositories/BaseRepository.php`

**Layer:** repository

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `Model $model` | `` |
| public | `all` | `array $columns = ['*']` | `Collection` |
| public | `find` | `int $id, array $columns = ['*']` | `?Model` |
| public | `findOrFail` | `int $id, array $columns = ['*']` | `Model` |
| public | `create` | `array $data` | `Model` |
| public | `update` | `Model $model, array $data` | `Model` |
| public | `delete` | `Model $model` | `bool` |
| public | `forceDelete` | `Model $model` | `bool` |
| public | `restore` | `Model $model` | `bool` |
| public | `paginate` | `int $perPage = 15, array $columns = ['*'], string $pageName = 'page', ?int $page = null` | `LengthAwarePaginator` |
| public | `query` | `` | `Builder` |

## References

- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
