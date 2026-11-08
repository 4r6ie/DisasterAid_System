---
tags: [code, backend, backend-repository]
---

# VolunteerRepository

`backend/app/Repositories/VolunteerRepository.php`

**Layer:** repository · **Module:** Volunteer

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `Volunteer $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getAvailable` | `array $skills = [], int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/Volunteer]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
