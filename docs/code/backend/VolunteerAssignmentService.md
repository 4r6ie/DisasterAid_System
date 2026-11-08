---
tags: [code, backend, backend-service]
---

# VolunteerAssignmentService

`backend/app/Services/VolunteerAssignmentService.php`

**Layer:** service · **Module:** VolunteerAssignment

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `__construct` | `protected VolunteerAssignmentRepository $repository` | `` |
| public | `getAll` | `array $filters = [], int $perPage = 15` | `LengthAwarePaginator` |
| public | `getById` | `int $id` | `?VolunteerAssignment` |
| public | `getByIdOrFail` | `int $id` | `VolunteerAssignment` |
| public | `create` | `array $data` | `VolunteerAssignment` |
| public | `update` | `VolunteerAssignment $assignment, array $data` | `VolunteerAssignment` |
| public | `delete` | `VolunteerAssignment $assignment` | `bool` |
| public | `getByVolunteer` | `int $volunteerId, int $limit = 50` | `Collection` |
| public | `getByDisasterIncident` | `int $disasterIncidentId, int $limit = 50` | `Collection` |

## References

- [[docs/code/backend/VolunteerAssignment]]
- [[docs/code/backend/VolunteerAssignmentRepository]]
- [[docs/code/frontend/Pagination]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
