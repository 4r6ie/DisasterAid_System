---
tags: [code, backend, backend-repository]
---

# DisasterIncidentRepository

`backend/app/Repositories/DisasterIncidentRepository.php`

**Layer:** repository · **Module:** DisasterIncident

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `DisasterIncident $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getNearby` | `float $lat, float $lng, float $radiusKm = 50, int $limit = 10` | `Collection` |
| public | `getStatistics` | `` | `array` |
| public | `getTrends` | `int $days = 30` | `array` |

## References

- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
