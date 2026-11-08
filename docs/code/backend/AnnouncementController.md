---
tags: [code, backend, backend-controller]
---

# AnnouncementController

`backend/app/Http/Controllers/API/V1/AnnouncementController.php`

**Layer:** controller · **Module:** Announcement

Extends `Controller`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected AnnouncementService $service` | `` |
| public | `index` | `` | `JsonResponse` |
| public | `store` | `StoreAnnouncementRequest $request` | `JsonResponse` |
| public | `show` | `int $id` | `JsonResponse` |
| public | `update` | `UpdateAnnouncementRequest $request, int $id` | `JsonResponse` |
| public | `destroy` | `int $id` | `JsonResponse` |
| public | `publish` | `int $id` | `JsonResponse` |
| public | `statistics` | `` | `JsonResponse` |

## References

- [[docs/code/backend/Announcement]]
- [[docs/code/backend/AnnouncementCollection]]
- [[docs/code/backend/AnnouncementResource]]
- [[docs/code/backend/AnnouncementService]]
- [[docs/code/backend/Controller]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/StoreAnnouncementRequest]]
- [[docs/code/backend/UpdateAnnouncementRequest]]
- [[docs/code/frontend/app]]
- [[docs/code/frontend/http]]
- [[docs/code/frontend/resources]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
