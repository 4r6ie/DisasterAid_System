---
tags: [code, backend, backend-controller]
---

# VolunteerController

`backend/app/Http/Controllers/API/V1/VolunteerController.php`

**Layer:** controller · **Module:** Volunteer

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected VolunteerService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreVolunteerRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateVolunteerRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `updateStatus` | `UpdateVolunteerStatusRequest $request, int $id` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/Controller]]
- [[docs/code/backend/StoreVolunteerRequest]]
- [[docs/code/backend/UpdateVolunteerRequest]]
- [[docs/code/backend/UpdateVolunteerStatusRequest]]
- [[docs/code/backend/Volunteer]]
- [[docs/code/backend/VolunteerCollection]]
- [[docs/code/backend/VolunteerResource]]
- [[docs/code/backend/VolunteerService]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
