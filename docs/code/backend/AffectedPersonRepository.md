---
tags: [code, backend, backend-repository]
---

# AffectedPersonRepository

`backend/app/Repositories/AffectedPersonRepository.php`

**Layer:** repository · **Module:** AffectedPerson

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `AffectedPerson $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getByDisasterIncident` | `int $disasterIncidentId, int $limit = 50` | `Collection` |
| public | `getByEvacuationCenter` | `int $evacuationCenterId, int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
