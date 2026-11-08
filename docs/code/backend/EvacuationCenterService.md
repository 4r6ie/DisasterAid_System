---
tags: [code, backend, backend-service]
---

# EvacuationCenterService

`backend/app/Services/EvacuationCenterService.php`

**Layer:** service · **Module:** EvacuationCenter

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected EvacuationCenterRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?EvacuationCenter` |
| public | `getByIdOrFail` | `int $id` | `EvacuationCenter` |
| public | `create` | `array $data` | `EvacuationCenter` |
| public | `update` | `EvacuationCenter $center, array $data` | `EvacuationCenter` |
| public | `delete` | `EvacuationCenter $center` | `bool` |
| public | `updateOccupancy` | `EvacuationCenter $center, int $occupancy` | `EvacuationCenter` |
| public | `getNearby` | `float $lat, float $lng, float $radiusKm = 50, int $limit = 10` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/backend/EvacuationCenterRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
