---
tags: [code, backend, backend-repository]
---

# ResponderRepository

`backend/app/Repositories/ResponderRepository.php`

**Layer:** repository · **Module:** Responder

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `Responder $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getAvailable` | `string $specialization = '', int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/Responder]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
