---
tags: [code, backend, backend-service]
---

# AnnouncementService

`backend/app/Services/AnnouncementService.php`

**Layer:** service · **Module:** Announcement

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected AnnouncementRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?Announcement` |
| public | `getByIdOrFail` | `int $id` | `Announcement` |
| public | `create` | `array $data` | `Announcement` |
| public | `update` | `Announcement $announcement, array $data` | `Announcement` |
| public | `delete` | `Announcement $announcement` | `bool` |
| public | `publish` | `Announcement $announcement` | `Announcement` |
| public | `unpublish` | `Announcement $announcement` | `Announcement` |
| public | `getActive` | `int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/Announcement]]
- [[docs/code/backend/AnnouncementRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
