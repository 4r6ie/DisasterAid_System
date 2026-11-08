---
tags: [code, backend, backend-repository]
---

# EmergencyReportRepository

`backend/app/Repositories/EmergencyReportRepository.php`

**Layer:** repository · **Module:** EmergencyReport

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `EmergencyReport $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getNearby` | `float $lat, float $lng, float $radiusKm = 50, int $limit = 10` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/EmergencyReport]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
