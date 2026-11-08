---
tags: [code, backend, backend-service]
---

# AffectedPersonService

`backend/app/Services/AffectedPersonService.php`

**Layer:** service · **Module:** AffectedPerson

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected AffectedPersonRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?AffectedPerson` |
| public | `getByIdOrFail` | `int $id` | `AffectedPerson` |
| public | `create` | `array $data` | `AffectedPerson` |
| public | `update` | `AffectedPerson $person, array $data` | `AffectedPerson` |
| public | `delete` | `AffectedPerson $person` | `bool` |
| public | `updateCondition` | `AffectedPerson $person, string $condition` | `AffectedPerson` |
| public | `assignEvacuationCenter` | `AffectedPerson $person, int $centerId` | `AffectedPerson` |
| public | `getByDisasterIncident` | `int $disasterIncidentId, int $limit = 50` | `Collection` |
| public | `getByEvacuationCenter` | `int $evacuationCenterId, int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/AffectedPersonRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
