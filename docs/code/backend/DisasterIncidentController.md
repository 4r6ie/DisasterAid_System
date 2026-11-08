---
tags: [code, backend, backend-controller]
---

# DisasterIncidentController

`backend/app/Http/Controllers/API/V1/DisasterIncidentController.php`

**Layer:** controller · **Module:** DisasterIncident

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected DisasterIncidentService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreDisasterIncidentRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateDisasterIncidentRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `assignResponder` | `AssignResponderRequest $request, int $id` | `JsonResponse` |
| public | `updateStatus` | `UpdateDisasterStatusRequest $request, int $id` | `JsonResponse` |
| public | `updateCasualties` | `UpdateCasualtiesRequest $request, int $id` | `JsonResponse` |
| public | `nearby` | `` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/AssignResponderRequest]]
- [[docs/code/backend/Controller]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/DisasterIncidentCollection]]
- [[docs/code/backend/DisasterIncidentResource]]
- [[docs/code/backend/DisasterIncidentService]]
- [[docs/code/backend/Responder]]
- [[docs/code/backend/StoreDisasterIncidentRequest]]
- [[docs/code/backend/UpdateCasualtiesRequest]]
- [[docs/code/backend/UpdateDisasterIncidentRequest]]
- [[docs/code/backend/UpdateDisasterStatusRequest]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
