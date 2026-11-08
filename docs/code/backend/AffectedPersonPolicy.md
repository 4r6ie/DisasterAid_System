---
tags: [code, backend, backend-policy]
---

# AffectedPersonPolicy

`backend/app/Policies/AffectedPersonPolicy.php`

**Layer:** policy · **Module:** AffectedPerson

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, AffectedPerson $person` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, AffectedPerson $person` | `bool` |
| public | `delete` | `User $user, AffectedPerson $person` | `bool` |
| public | `updateCondition` | `User $user, AffectedPerson $person` | `bool` |
| public | `assignEvacuationCenter` | `User $user, AffectedPerson $person` | `bool` |

## References

- [[docs/code/backend/AffectedPerson]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
