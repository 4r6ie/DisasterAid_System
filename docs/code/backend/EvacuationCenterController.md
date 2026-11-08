---
tags: [code, backend, backend-controller]
---

# EvacuationCenterController

`backend/app/Http/Controllers/API/V1/EvacuationCenterController.php`

**Layer:** controller · **Module:** EvacuationCenter

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected EvacuationCenterService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreEvacuationCenterRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateEvacuationCenterRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `updateOccupancy` | `UpdateOccupancyRequest $request, int $id` | `JsonResponse` |
| public | `nearby` | `` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/Controller]]
- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/backend/EvacuationCenterCollection]]
- [[docs/code/backend/EvacuationCenterResource]]
- [[docs/code/backend/EvacuationCenterService]]
- [[docs/code/backend/StoreEvacuationCenterRequest]]
- [[docs/code/backend/UpdateEvacuationCenterRequest]]
- [[docs/code/backend/UpdateOccupancyRequest]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
