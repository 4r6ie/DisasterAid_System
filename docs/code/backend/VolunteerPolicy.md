---
tags: [code, backend, backend-policy]
---

# VolunteerPolicy

`backend/app/Policies/VolunteerPolicy.php`

**Layer:** policy · **Module:** Volunteer

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, Volunteer $volunteer` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, Volunteer $volunteer` | `bool` |
| public | `delete` | `User $user, Volunteer $volunteer` | `bool` |
| public | `updateStatus` | `User $user, Volunteer $volunteer` | `bool` |

## References

- [[docs/code/backend/User]]
- [[docs/code/backend/Volunteer]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
