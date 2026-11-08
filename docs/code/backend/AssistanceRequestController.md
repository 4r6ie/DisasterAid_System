---
tags: [code, backend, backend-controller]
---

# AssistanceRequestController

`backend/app/Http/Controllers/API/V1/AssistanceRequestController.php`

**Layer:** controller · **Module:** AssistanceRequest

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected AssistanceRequestService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreAssistanceRequestRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateAssistanceRequestRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `approve` | `int $id` | `JsonResponse` |
| public | `dispatch` | `int $id` | `JsonResponse` |
| public | `complete` | `int $id` | `JsonResponse` |
| public | `reject` | `RejectAssistanceRequestRequest $request, int $id` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/AssistanceRequest]]
- [[docs/code/backend/AssistanceRequestCollection]]
- [[docs/code/backend/AssistanceRequestResource]]
- [[docs/code/backend/AssistanceRequestService]]
- [[docs/code/backend/Controller]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/RejectAssistanceRequestRequest]]
- [[docs/code/backend/StoreAssistanceRequestRequest]]
- [[docs/code/backend/UpdateAssistanceRequestRequest]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
