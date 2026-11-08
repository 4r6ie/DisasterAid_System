---
tags: [code, backend, backend-controller]
---

# ResponderController

`backend/app/Http/Controllers/API/V1/ResponderController.php`

**Layer:** controller · **Module:** Responder

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected ResponderService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreResponderRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateResponderRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `updateStatus` | `UpdateResponderStatusRequest $request, int $id` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/Controller]]
- [[docs/code/backend/Responder]]
- [[docs/code/backend/ResponderCollection]]
- [[docs/code/backend/ResponderResource]]
- [[docs/code/backend/ResponderService]]
- [[docs/code/backend/StoreResponderRequest]]
- [[docs/code/backend/UpdateResponderRequest]]
- [[docs/code/backend/UpdateResponderStatusRequest]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
