---
tags: [code, backend, backend-repository]
---

# AnnouncementRepository

`backend/app/Repositories/AnnouncementRepository.php`

**Layer:** repository · **Module:** Announcement

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `Announcement $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getActive` | `int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/Announcement]]
- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
