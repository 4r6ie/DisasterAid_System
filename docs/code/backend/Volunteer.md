---
tags: [code, backend, backend-model]
---

# Volunteer

`backend/app/Models/Volunteer.php`

**Layer:** model · **Module:** Volunteer

Extends `Model`.

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `getActivitylogOptions` | `` | `LogOptions` |
| public | `scopeByStatus` | `$query, string $status` | `` |
| public | `scopeAvailable` | `$query` | `` |
| public | `assignments` | `` | `` |
| public | `activeAssignments` | `` | `` |
| public | `hasSkill` | `string $skill` | `bool` |
| public | `isAvailableOn` | `string $day, string $period` | `bool` |

## References

- [[docs/code/backend/VolunteerAssignment]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
