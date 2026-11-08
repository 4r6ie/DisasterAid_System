---
tags: [code, backend, backend-service]
---

# VolunteerService

`backend/app/Services/VolunteerService.php`

**Layer:** service · **Module:** Volunteer

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected VolunteerRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?Volunteer` |
| public | `getByIdOrFail` | `int $id` | `Volunteer` |
| public | `create` | `array $data` | `Volunteer` |
| public | `update` | `Volunteer $volunteer, array $data` | `Volunteer` |
| public | `delete` | `Volunteer $volunteer` | `bool` |
| public | `updateStatus` | `Volunteer $volunteer, string $status` | `Volunteer` |
| public | `getAvailable` | `array $skills = [], int $limit = 50` | `Collection` |
| public | `getStatistics` | `` | `array` |

## References

- [[docs/code/backend/Volunteer]]
- [[docs/code/backend/VolunteerRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
