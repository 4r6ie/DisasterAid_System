---
tags: [code, backend, backend-policy]
---

# AnnouncementPolicy

`backend/app/Policies/AnnouncementPolicy.php`

**Layer:** policy · **Module:** Announcement

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, Announcement $announcement` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, Announcement $announcement` | `bool` |
| public | `delete` | `User $user, Announcement $announcement` | `bool` |
| public | `publish` | `User $user, Announcement $announcement` | `bool` |

## References

- [[docs/code/backend/Announcement]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
