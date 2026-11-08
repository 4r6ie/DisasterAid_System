---
tags: [code, backend, backend-repository]
---

# VolunteerAssignmentRepository

`backend/app/Repositories/VolunteerAssignmentRepository.php`

**Layer:** repository · **Module:** VolunteerAssignment

Extends `BaseRepository`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `VolunteerAssignment $model` | `` |
| public | `getFiltered` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getByVolunteer` | `int $volunteerId, int $limit = 50` | `Collection` |
| public | `getByDisasterIncident` | `int $disasterIncidentId, int $limit = 50` | `Collection` |

## References

- [[docs/code/backend/BaseRepository]]
- [[docs/code/backend/DisasterIncident]]
- [[docs/code/backend/Volunteer]]
- [[docs/code/backend/VolunteerAssignment]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
