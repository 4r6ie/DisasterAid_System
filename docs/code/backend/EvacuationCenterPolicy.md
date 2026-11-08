---
tags: [code, backend, backend-policy]
---

# EvacuationCenterPolicy

`backend/app/Policies/EvacuationCenterPolicy.php`

**Layer:** policy · **Module:** EvacuationCenter

## Methods

| Visibility | Method | Parameters | Returns |
| --- | --- | --- | --- |
| public | `viewAny` | `User $user` | `bool` |
| public | `view` | `User $user, EvacuationCenter $center` | `bool` |
| public | `create` | `User $user` | `bool` |
| public | `update` | `User $user, EvacuationCenter $center` | `bool` |
| public | `delete` | `User $user, EvacuationCenter $center` | `bool` |
| public | `updateOccupancy` | `User $user, EvacuationCenter $center` | `bool` |

## References

- [[docs/code/backend/EvacuationCenter]]
- [[docs/code/backend/User]]
- [[docs/code/frontend/app]]

## Hubs

- [[Code Map]] · [[docs/code/backend]]
