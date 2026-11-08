---
tags: [code, backend, backend-controller]
---

# AffectedPersonController

`backend/app/Http/Controllers/API/V1/AffectedPersonController.php`

**Layer:** controller · **Module:** AffectedPerson

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected AffectedPersonService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreAffectedPersonRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateAffectedPersonRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `updateCondition` | `UpdateConditionRequest $request, int $id` | `JsonResponse` |
| public | `assignEvacuationCenter` | `AssignEvacuationCenterRequest $request, int $id` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/AffectedPersonCollection]]
- [[docs/code/backend/AffectedPersonResource]]
- [[docs/code/backend/AffectedPersonService]]
- [[docs/code/backend/AssignEvacuationCenterRequest]]
- [[docs/code/backend/Controller]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/backend/StoreAffectedPersonRequest]]
- [[docs/code/backend/UpdateAffectedPersonRequest]]
- [[docs/code/backend/UpdateConditionRequest]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
