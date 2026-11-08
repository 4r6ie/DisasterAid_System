---
tags: [code, backend, backend-repository]
---

# ReliefItemRepository

`backend/app/Repositories/ReliefItemRepository.php`

**Layer:** repository · **Module:** ReliefItem

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `ReliefItem $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getLowStock` | `int $limit = 50` | `Collection` |
| public | `getExpiringSoon` | `int $days = 30, int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/ReliefItem]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
